<template>
  <div class="skb-body container">
    <div v-show="loading">
      <div class="loader-container-full">
        <div class="loader" />
      </div>
    </div>
    <a href="/services/list">
      <button
        title="Annulez "
        type="button"
        class="w-40px p-0 rounded-circle btn-close btn"
      >
        <i class="fas fa-times " />
      </button>
    </a>
    <form>
      <div class="">
        <div class="register-card w-100 h-100">
          <!-- First row  -->
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <fieldset
                  id="title"
                  class="title"
                >
                  <label class="fontUbuntuItalic fontSize14">{{ this.$t('company.fields.name') }}</label>
                  <input
                    v-model="formFields.title"
                    v-validate="'required'"
                    type="text"
                    name="title"
                    class="form-control"
                    :placeholder="$t('besoin.placeholder.title')"
                  >
                  <div
                    v-for="errorText in formErrors.title"
                    :key="'title_' + errorText"
                  >
                    <span class="fontUbuntuItalic fontSize13 red-skb">{{ errorText }}</span>
                  </div>
                </fieldset>
              </div>
            </div>
          </div>
          <!-- second row -->
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <fieldset
                  id="category"
                  class="category"
                >
                  <label class="fontSize16">{{ $t('commons.label.category') }}</label>
                  <multiselect
                    v-model="formFields.category"
                    v-validate="'required'"
                    :disabled="besoinId !== null"
                    :options="category"
                    :options-limit="300"
                    name="category"
                    :placeholder="$t('besoin.placeholder.category')"
                    :searchable="false"
                    :close-on-select="true"
                    :show-labels="false"
                    label="name"
                    track-by="name"
                    class="fontAlice"
                  />
                  <span
                    v-if="besoinId"
                    class="fontSize14 italic"
                  >
                    {{ $t('besoin.not_editable') }}
                  </span>
                </fieldset>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <fieldset
                  id="sousCategory"
                  class="sousCategory"
                >
                  <label class="fontSize16">{{ $t('commons.label.activity') }}</label>
                  <multiselect
                    v-model="formFields.sousCategorys"
                    v-validate="'required'"
                    :disabled="formFields.category === null || besoinId !== null"
                    :options="sousCategory"
                    :options-limit="300"
                    name="sousCategory"
                    :placeholder="$t('besoin.placeholder.activity')"
                    :searchable="false"
                    :close-on-select="false"
                    :show-labels="false"
                    label="name"
                    track-by="name"
                    class="fontAlice"
                    :multiple="true"
                  />
                  <span
                    v-if="besoinId"
                    class="fontSize14 italic"
                  >
                    {{ $t('besoin.not_editable') }}
                  </span>
                </fieldset>
              </div>
            </div>
          </div>

          <!-- third row -->
          <div class="row mb-3">
            <div class="col-12">
              <div class="form-group">
                <fieldset
                  id="description"
                  class="description"
                >
                  <label class="fontSize16">{{ $t('besoin.label.description') }}</label>
                  <textarea
                    v-model="formFields.description"
                    v-validate="'required'"
                    class="form-control"
                    name="description"
                    :maxlength="1000"
                    :rows="8"
                    :placeholder="$tc('commons.maximum_n_characters', 1000)"
                  />
                  <div
                    :class="!$getNbCharactersLeft(formFields.description, 2000) ? 'red-skb' : 'black-skb'"
                    class="text-right pt-2 fontSize12"
                  >
                    {{ $tc('commons.n_charaters_left', $getNbCharactersLeft(formFields.description, 1000)) }}
                  </div>
                  <div
                    v-for="errorText in formErrors.description"
                    :key="'description_' + errorText"
                    class="line-height-1"
                  >
                    <span>{{ errorText }} </span>
                  </div>
                </fieldset>
              </div>
            </div>
          </div>
        </div>


        <div class="row my-3">
          <div class="col-6 offset-3">
            <button
              type="button"
              class="btn button_skb fontUbuntuItalic"
              @click="$validateForm()"
            >
              {{ besoinId ? this.$t('commons.edit') : this.$t('commons.create') }}
            </button>
          </div>
        </div>
      </div>
    </form>

  </div>
</template>
<script>
  import axios from 'axios';
  import _ from 'lodash';

  export default {
    props: {
      utilisateurId: {
        type: Number,
        default: null
      },
      besoinId: {
        type: Number,
        default: null
      }
    },
    data() {
      return {
        API_URL: this.besoinId ? '/api/besoins/' + this.besoinId : '/api/besoins',
        loading: true,
        category: [],
        sousCategory: [],
        formFields: {
          title: null,
          description: null,
          category: null,
          sousCategorys: null,
          author: null
        },
        formErrors: {
          title: null,
          description: null,
          category: null,
          sousCategorys: null
        }
      };
    },
    computed: {
      categoryIsSet() {
        return this.formFields.category;
      }
    },
    watch: {
      /**
       * @param {Object} newValue
       */
      categoryIsSet(newValue) {
        if (newValue) {
          this.sousCategory = newValue.sous_categorys;
        }
      },
    },
    created() {
      let promises = [];
      //WARNING nom du serial group à revoir ?

      promises.push(axios.get('/api/admin/categories'));
      if (this.besoinId) {
        promises.push(axios.get('/api/besoins/' + this.besoinId));
      }
      return Promise.all(promises).then(res => {
        this.category = _.cloneDeep(res[0].data);
        if (this.besoinId) {
          let besoin = _.cloneDeep(res[1].data);
          this.$removeFieldsNotInForm(besoin, Object.keys(this.formFields));
          this.$setEditForm(besoin);
        }
        this.loading = false;

      }).catch(e => {
        this.$handleError(e);
        this.loading = false;
      });
    },
    methods: {
      submitForm() {
        this.loading = true;
        this.formFields.author = _.cloneDeep(this.utilisateurId);
        let formData = this.$getFormFieldsData(this.formFields);
        return axios.post(this.API_URL, formData).then(response => {
          this.loading = false;
          window.location.assign(response.headers.location);
        }).catch(e => {
          if (e.response && e.response.status && e.response.status == 400) {
            this.$handleFormError(e.response.data);
          }
          this.loading = false;
        });
      },
    },
  };
</script>
