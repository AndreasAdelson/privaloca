<template>
  <div>
    <div class="container">
      <a href="/admin/utilisateur">
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
                    id="email"
                    class="email"
                  >
                    <label class="fontUbuntu fontSize16">{{ this.$t('admin.user.fields.email') }}</label>
                    <input
                      v-validate="'required|email'"
                      type="text"
                      name="email"
                      class="form-control"
                      placeholder="Enter email"
                      v-model="formFields.email"
                    >
                    <div
                      v-for="errorText in formErrors.email"
                      :key="'email_' + errorText"
                    >
                      <span class="fontUbuntu fontSize13 red-skb">{{ errorText }}</span>
                    </div>
                  </fieldset>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <fieldset
                    id="firstName"
                    class="firstName"
                  >
                    <label class="fontUbuntu fontSize16">{{ this.$t('admin.user.fields.first_name') }}</label>
                    <input
                      v-validate="'required'"
                      name="firstName"
                      type="text"
                      class="form-control"
                      placeholder="Enter first name"
                      v-model="formFields.firstName"
                    >
                    <div
                      v-for="errorText in formErrors.firstName"
                      :key="'firstName_' + errorText"
                    >
                      <span class="fontUbuntu fontSize13 red-skb">{{errorText }}</span>
                    </div>
                  </fieldset>
                </div>
              </div>
            </div>
            <!-- Second row  -->
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <fieldset
                    id="lastName"
                    class="lastName"
                  >
                    <label class="fontUbuntu fontSize16">{{ this.$t('admin.user.fields.last_name') }}</label>
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Enter first name"
                      v-model="formFields.lastName"
                    >
                    <div
                      v-for="errorText in formErrors.lastName"
                      :key="'lastName_' + errorText"
                    >
                      <span class="fontUbuntu fontSize13 red-skb">{{errorText }}</span>
                    </div>
                  </fieldset>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <fieldset
                    id="login"
                    class="login"
                  >
                    <label class="fontUbuntu fontSize16">{{ this.$t('admin.user.fields.login') }}</label>
                    <input
                      v-validate="'required_login'"
                      type="text"
                      name="login"
                      class="form-control"
                      placeholder="Enter login"
                      v-model="formFields.login"
                    >
                    <div
                      v-for="errorText in formErrors.login"
                      :key="'login_' + errorText"
                    >
                      <span class="fontUbuntu fontSize13 red-skb">{{errorText }}</span>
                    </div>
                  </fieldset>
                </div>
              </div>
            </div>
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
        email: null,
        firstName: null,
        lastName: null,
        login: null,
      },
      formErrors: {
        email: [],
        firstName: [],
        lastName: [],
        login: []
      }
    };
  },
  props: {
    utilisateurId: {
      type: Number,
      default: null,
    }
  },
  created () {
    if (this.utilisateurId) {
      return axios.get("/api/admin/utilisateurs/" + this.utilisateurId)
        .then(response => {
          this.$setEditForm(response.data);
        }).catch(e => {
          console.log(e);
        });
    }
  },
  methods: {
    submitForm () {
      return axios.post("/api/admin/utilisateurs/" + this.utilisateurId, this.formFields)
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
