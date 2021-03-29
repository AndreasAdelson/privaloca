<template>
  <div>
    <div class="container skb-body">
      <div v-show="loading">
        <div class="loader-container-full">
          <div class="loader" />
        </div>
      </div>
      <a href="/admin/utilisateur">
        <button
          :title="$t('commons.undo_change')"
          type="button"
          class="w-40px p-0 rounded-circle btn-close btn"
        >
          <font-awesome-icon :icon="['fas', 'times']" />
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
                    <label class="fontUbuntuItalic fontSize16">{{ this.$t('user.fields.email') }}</label>
                    <input
                      v-model="formFields.email"
                      v-validate="'required|email'"
                      type="text"
                      name="email"
                      class="form-control"
                      placeholder="Enter email"
                    >
                    <div
                      v-for="errorText in formErrors.email"
                      :key="'email_' + errorText"
                    >
                      <span class="fontUbuntuItalic fontSize13 red-skb">{{ errorText }}</span>
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
                    <label class="fontUbuntuItalic fontSize16">{{ this.$t('user.fields.first_name') }}</label>
                    <input
                      v-model="formFields.firstName"
                      v-validate="'required'"
                      name="firstName"
                      type="text"
                      class="form-control"
                      placeholder="Enter first name"
                    >
                    <div
                      v-for="errorText in formErrors.firstName"
                      :key="'firstName_' + errorText"
                    >
                      <span class="fontUbuntuItalic fontSize13 red-skb">{{ errorText }}</span>
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
                    <label class="fontUbuntuItalic fontSize16">{{ this.$t('user.fields.last_name') }}</label>
                    <input
                      v-model="formFields.lastName"
                      type="text"
                      class="form-control"
                      placeholder="Enter first name"
                    >
                    <div
                      v-for="errorText in formErrors.lastName"
                      :key="'lastName_' + errorText"
                    >
                      <span class="fontUbuntuItalic fontSize13 red-skb">{{ errorText }}</span>
                    </div>
                  </fieldset>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <fieldset
                    id="username"
                    class="username"
                  >
                    <label class="fontUbuntuItalic fontSize16">{{ this.$t('user.fields.username') }}</label>
                    <input
                      v-model="formFields.username"
                      v-validate="'required_username'"
                      type="text"
                      name="username"
                      class="form-control"
                      placeholder="Enter username"
                    >
                    <div
                      v-for="errorText in formErrors.username"
                      :key="'username_' + errorText"
                    >
                      <span class="fontUbuntuItalic fontSize13 red-skb">{{ errorText }}</span>
                    </div>
                  </fieldset>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 offset-3">
                <button
                  type="button"
                  class="btn button_skb fontUbuntuItalic"
                  @click="$validateForm()"
                >
                  {{ this.$t('commons.edit') }}
                </button>
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
    props: {
      utilisateurId: {
        type: Number,
        default: null,
      }
    },
    data() {
      return {
        loading: false,
        formFields: {
          email: null,
          firstName: null,
          lastName: null,
          username: null,
        },
        formErrors: {
          email: [],
          firstName: [],
          lastName: [],
          username: []
        }
      };
    },
    created() {
      if (this.utilisateurId) {
        this.loading = true;
        return axios.get('/api/admin/utilisateurs/' + this.utilisateurId)
          .then(response => {
            this.$setEditForm(response.data);
            this.loading = false;
          }).catch(e => {
            console.log(e);
            this.loading = false;
          });
      }
    },
    methods: {
      submitForm() {
        return axios.post('/api/admin/utilisateurs/' + this.utilisateurId, this.formFields)
          .then(response => {
            window.location.assign(response.headers.location);
          }).catch(e => {
            if (e.response && e.response.status && e.response.status == 400) {
              this.$handleFormError(e.response.data);
            }
          });
      },
    },
  };
</script>
