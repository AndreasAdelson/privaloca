<template>
  <div>
    <div class="container">
      <a href="/admin/fonction">
        <button
          title="Annulez les modifications"
          type="button"
          class="w-40px p-0 rounded-circle btn-close btn"
        >
          <i class="fas fa-times "></i>
        </button>
      </a>
      <form>
        <div class="">
          <div class="register-card w-100 h-100">
            <!-- First row  -->
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <fieldset
                    id="code"
                    class="code"
                  >
                    <label class="fontUbuntu fontSize16">{{ this.$t('admin.fonction.fields.code') }}</label>
                    <input
                      v-validate="'required'"
                      type="text"
                      name="code"
                      class="form-control"
                      :placeholder="$t('admin.fonction.placeholder.code')"
                      v-model="formFields.code"
                    >
                    <div
                      v-for="errorText in formErrors.code"
                      :key="'code_' + errorText"
                    >
                      <span class="fontUbuntu fontSize13 red-skb">{{ errorText }}</span>
                    </div>
                  </fieldset>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <fieldset
                    id="description"
                    class="description"
                  >
                    <label class="fontUbuntu fontSize16">{{ this.$t('admin.fonction.placeholder.description') }}</label>
                    <input
                      v-validate="'required'"
                      name="description"
                      type="text"
                      class="form-control"
                      :placeholder="$t('admin.fonction.placeholder.description')"
                      v-model="formFields.description"
                    >
                    <div
                      v-for="errorText in formErrors.description"
                      :key="'description_' + errorText"
                    >
                      <span class="fontUbuntu fontSize13 red-skb">{{errorText }}</span>
                    </div>
                  </fieldset>
                </div>
              </div>
            </div>
            <!-- Second row  -->
            <div class="row">
              <div class="col-6 offset-3">
                <button
                  type="button"
                  class="btn button_skb fontUbuntu"
                  @click="$validateForm()"
                >{{ this.$t('commons.edit') }}</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import axios from 'axios';
import validatorRulesMixin from 'mixins/validatorRulesMixin';

export default {
  mixins: [
    validatorRulesMixin
  ],
  data () {
    return {
      formFields: {
        code: null,
        description: null,
      },
      formErrors: {
        code: [],
        description: [],
      }
    };
  },
  props: {
  },

  methods: {
    submitForm () {
      return axios.post("/api/admin/fonctions", this.formFields)
        .then(response => {
          window.location.assign(response.headers.location);
        }).catch(e => {
          if (e.response && e.response.status && e.response.status == 400) {
            this.$handleFormError(e.response.data);
          }
        });
    },
  },
}
</script>
