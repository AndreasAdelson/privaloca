import _ from 'lodash';

const CnsFormUtils = {
  install(Vue, options) {
    /**
     * Remove all fields of given object which are not in the form fields, except the id.
     * Called when editing an etude
     * @param {object} entity
     * @param {array} keptFields
     */
    Vue.prototype.$removeFieldsNotInForm = function (entity, keptFields) {
      Object.keys(entity).forEach(field => {
        if (!keptFields.includes(field)) {
          delete entity[field];
        }
      });
    };

    Vue.prototype.$handleFormError = function (errorsData) {
      let receivedFormError = errorsData.errors.children;
      Object.keys(receivedFormError).forEach(field => {
        const fieldErrors = receivedFormError[field].errors;
        if (fieldErrors) {
          this.$addErrorInFormError(field, fieldErrors);
        }
      });
    };

    Vue.prototype.$addErrorInFormError = function (field, error) {
      if (error) {
        this.formErrors[field] = error;
      }
      console.log(field, 'field');
      let fieldElementHtml = document.getElementsByClassName(field)[0];
      console.log(fieldElementHtml, 'fieldElementHtml');

      let formElement =
        fieldElementHtml.getElementsByTagName('input')[0] ||
        fieldElementHtml.getElementsByTagName('textarea')[0];
      if (formElement) {
        formElement.classList.add('border-danger-skb');
      }
    };

    Vue.prototype.$validateForm = function (newLocationHash) {
      this.$removeFormErrors();
      return this.$validator.validate().then(result => {
        if (result) {
          this.submitForm();
        } else {
          this.$validator.errors.items.forEach(error => {
            let fieldErrors = _.get(this.formErrors, error.field);
            if (fieldErrors === undefined) {
              fieldErrors = [];
            }

            //add custom rules gestion here -> see connectus
            const customRulesList = [];
            let errorMessage = error.msg;
            if (!customRulesList.includes(error.rule)) {
              errorMessage = this.$t('error_message_' + error.rule);
            }
            if (fieldErrors instanceof Array) {
              fieldErrors.push(errorMessage);
            } else {
              this.$set(fieldErrors, '-1', errorMessage);
            }
            let formErrors = _.cloneDeep(this.formErrors);
            _.set(formErrors, error.field, fieldErrors);
            this.formErrors = _.cloneDeep(formErrors);
            this.$addErrorInFormError(error.field);
          });
          if (newLocationHash) {
            window.location.hash = newLocationHash;
          }
        }
      })
    };

    Vue.prototype.$removeFormErrors = function () {
      Object.keys(this.formErrors).forEach(field => {
        this.$removeFieldErrors(field);
      });
    };

    Vue.prototype.$removeFieldErrors = function (field) {
      _.set(this.formErrors, field, []);
      let fieldsetElement = document.getElementsByClassName(field)[0];
      if (fieldsetElement) {
        let formElement =
          fieldsetElement.getElementsByTagName('input')[0] ||
          fieldsetElement.getElementsByTagName('textarea')[0];
        if (formElement) {
          formElement.classList.remove('border-danger-skb');
        }
      }
    };

    Vue.prototype.$setEditForm = function (data) {
      console.log(data, 'data');
      Object.keys(this.formFields).forEach(field => {
        let underscoreField = this.$camelCaseToUnderscoreCase(field);
        this.formFields[field] = data[underscoreField];
      });
    };

    Vue.prototype.$camelCaseToUnderscoreCase = function (field) {
      return field.replace(/([A-Z])/g, "_$1").toLowerCase();
    };

    /**
     * Redirect to given URL
     * @param {String} url
     */
    Vue.prototype.$goTo = function (url) {
      window.location.assign(url);
    };

    Vue.prototype.$getTransformedValue = function (value) {
      let transformedValue = undefined;
      if (value instanceof Date) {
        transformedValue = {
          day: value.getDate(),
          month: value.getMonth() + 1,
          year: value.getFullYear(),
        };
      } else if (value instanceof Array) {
        if (value.length) {
          let newValueArray = new Array();
          value.forEach(elem => {
            const tsf = this.$getTransformedValue(elem);
            if (tsf !== undefined) {
              newValueArray.push(tsf);
            }
          });
          if (newValueArray.length) {
            transformedValue = newValueArray;
          }
        }
      } else if (value instanceof Object) {
        if (Object.keys(value).length) {
          if (value.id) {
            transformedValue = value.id;
          } else {
            let newValueObject = new Object();
            for (let key in value) {
              const tsf = this.$getTransformedValue(value[key]);
              if (tsf !== undefined) {
                newValueObject[key] = tsf;
              }
            }
            if (Object.keys(newValueObject).length) {
              transformedValue = newValueObject;
            }
          }
        }
      } else if (![null, undefined, '', false].includes(value)) {
        transformedValue = value;
      }
      return transformedValue;
    };

    /**
     * Put formFields values into a formData object, in order to sent it to a PHP form.
     * @param {Object} formFields
     * @param {FormData} formData
     */
    Vue.prototype.$getFormFieldsData = function (formFields = this.formFields, formData = new FormData()) {
      let transformedFormFields = this.$getTransformedValue(formFields);
      _.forEach(_.keys(transformedFormFields), field => {
        if (transformedFormFields[field] instanceof Array) {
          transformedFormFields[field].forEach(elem => {
            formData.append(field + '[]', elem);
          });
        } else if (transformedFormFields[field] instanceof Object) {
          for (let key in transformedFormFields[field]) {
            formData.append(`${field}[${key}]`, transformedFormFields[field][key]);
          }
        } else {
          formData.append(field, transformedFormFields[field]);
        }
      });
      return formData;
    };

    /**
     * Called when the event "selectedItemsChange" is triggered from DualList component.
     * Set the formFields[fieldName] variable to the value given in parameter.
     * @param {Object} eventArguments Event arguments and other VueJS properties about the event. Must be passed so 'this' is defined.
     * @param {Array} eventArguments[0] Selected items.
     * @param {String} fieldName
     */
    Vue.prototype.$onSelectedItemsChange = function (eventArguments, fieldName) {
      this.formFields[fieldName] = _.cloneDeep(eventArguments[0]);
    };

    /**
     * Called whenever receiving the « after-create » event from the any new entity modal component.
     * Add the given entity to the formFields[fieldName] list
     * @param {Object} eventArguments Event arguments and other VueJS properties about the event. Must be passed so 'this' is defined.
     * @param {Object} eventArguments[0] The entity object
     * @param {String} fieldName The name of the entity
     * @param {Object} formFields The object containing fieldName entities  The name of the entity
     */
    Vue.prototype.$onAfterCreateEntity = function (eventArguments, fieldName, formFields) {
      const nextKey = Math.max(-1, ...Object.keys(formFields[fieldName])) + 1;
      Vue.set(formFields[fieldName], nextKey, eventArguments[0]);
    };
  }
}

export default CnsFormUtils;
