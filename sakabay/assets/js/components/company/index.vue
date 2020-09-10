<template>
  <div class="container">
    <a href="/home">
      <button
        title="Annulez "
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
                  id="nom"
                  class="nom"
                >
                  <label class="fontUbuntu fontSize14">{{ this.$t('admin.role.fields.nom') }}</label>
                  <input
                    v-validate="'required'"
                    type="text"
                    name="name"
                    class="form-control"
                    :placeholder="$t('admin.role.placeholder.nom')"
                    v-model="formFields.nom"
                  >
                  <div
                    v-for="errorText in formErrors.nom"
                    :key="'name_' + errorText"
                  >
                    <span class="fontUbuntu fontSize13 red-skb">{{ errorText }}</span>
                  </div>
                </fieldset>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <fieldset
                  id="numSiret"
                  class="numSiret"
                >
                  <label class="fontUbuntu fontSize14">{{ this.$t('admin.role.fields.numSiret') }}</label>
                  <input
                    v-validate="'required'"
                    name="numSiret"
                    type="text"
                    class="form-control"
                    :placeholder="$t('admin.role.placeholder.numSiret')"
                    v-model="formFields.numSiret"
                  >
                  <div
                    v-for="errorText in formErrors.numSiret"
                    :key="'code_' + errorText"
                  >
                    <span class="fontUbuntu fontSize13 red-skb">{{ errorText }}</span>
                  </div>
                </fieldset>
              </div>
            </div>
          </div>
          <!-- second row -->
          <!--  <div class="row">
            <div class="col-6">
              <div class="form-group">
                <fieldset
                  id="nomCostumer"
                  class="nomCostumer"
                >
                  <label class="fontUbuntu fontSize14">{{ this.$t('admin.role.fields.nomCostumer') }}</label>
                  <input
                    v-validate="'required'"
                    type="text"
                    name="nomCostumer"
                    class="form-control"
                    :placeholder="$t('admin.role.placeholder.nomCostumer')"
                    v-model="formFields.nomCostumer"
                  >
                  <div
                    v-for="errorText in formErrors.nomCostumer"
                    :key="'name_' + errorText"
                  >
                    <span class="fontUbuntu fontSize13 red-skb">{{ errorText }}</span>
                  </div>
                </fieldset>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <fieldset
                  id="lastNameCostumer"
                  class="lastNameCostumer"
                >
                  <label class="fontUbuntu fontSize14">{{ this.$t('admin.role.fields.lastNameCostumer') }}</label>
                  <input
                    v-validate="'required'"
                    name="lastNameCostumer"
                    type="text"
                    class="form-control"
                    :placeholder="$t('admin.role.placeholder.lastNameCostumer')"
                    v-model="formFields.lastNameCostumer"
                  >
                  <div
                    v-for="errorText in formErrors.lastNameCostumer"
                    :key="'code_' + errorText"
                  >
                    <span class="fontUbuntu fontSize13 red-skb">{{ errorText }}</span>
                  </div>
                </fieldset>
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-1">
              <b-img
                v-if="urlImageProfil"
                class="rounded-circle logo-size-75"
                :src="urlImageProfil"
              />
              <avatar
                v-else-if="utilisateur"
                class="p-0"
                :username="utilisateur.first_name + ' ' + utilisateur.last_name"
                :size="75"
              ></avatar>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <fieldset
                  id="email"
                  class="email"
                >
                  <label class="fontUbuntu fontSize14">{{ this.$t('admin.user.fields.email') }}</label>
                  <input
                    v-validate="'required|email'"
                    type="text"
                    name="email"
                    class="form-control"
                    :placeholder="$t('admin.user.placeholder.email')"
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
            <div class="col-6 my-auto">
              <div class="form-group mb-0">
                <fieldset
                  id="imageProfil"
                  class="imageProfil"
                >
                  <input
                    name="imageProfil"
                    type="file"
                    @change="onFileSelected"
                    ref="imageProfil"
                  >
                  <div
                    v-for="errorText in formErrors.imageProfil"
                    :key="'imageProfil_' + errorText"
                  >
                    <span class="fontUbuntu fontSize13 red-skb">{{errorText }}</span>
                  </div>
                </fieldset>
              </div>
            </div>
          </div>
          -->
          <div class="row">
            <div class="col-12">

              <fieldset
                id="category"
                class="category"
              >

                <multiselect
                  v-model="formFields.category"
                  :options="category"
                  placeholder="Selectionner categorie"
                  :searchable="false"
                  :close-on-select="false"
                  :show-labels="false"
                  label="name"
                  track-by="name"
                >
                </multiselect>

              </fieldset>
            </div>
          </div>
          <div class="row my-3">
            <div class="col-6 offset-3">
              <button
                type="button"
                class="btn button_skb fontUbuntu"
                @click="$validateForm()"
              >{{ this.$t('commons.create') }}</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
import axios from 'axios';
import validatorRulesMixin from 'mixins/validatorRulesMixin';
import adminFormMixin from 'mixins/adminFormMixin';
import DualList from 'components/commons/dual-list';
export default {
  components: {
    DualList
  },
  mixins: [
    validatorRulesMixin,
    adminFormMixin
  ],
  props: {
    categoryId: {
      type: Number,
      default: null,
    }
  },
  data () {
    return {
      API_URL: '/api/companies',
      fonctionsAtCreation: null,
      fonctions: [],
      formFields: {
        nom: null,
        numSiret: null,
        // nomCostumer: null,
        // lastNameCostumer: null,
        // email: null,
        // utilisateur: [],
        category: null,
      },
      formErrors: {
        nom: [],
        numSiret: [],
        // nomCostumer: [],
        // lastNameCostumer: [],
        // email: [],
        // imageProfil: [],
        category: []
      },
      // urlImageProfil: null,
      // imageProfilSelected: null,
      // utilisateur: null,
      // imageName: '',
      // value: null,
      category: []
    };
  },
  created () {
    return axios.get("/api/admin/categories")
      .then(response => {
        this.category = response.data;
        console.log(response.data);
      }).catch(e => {
        console.log(e);
      });
  },
  methods: {
    // onFileSelected () {
    //   this.imageProfilSelected = this.$refs.imageProfil.files[0];
    //   this.imageName = this.$refs.imageProfil.files[0].name;
    //   this.urlImageProfil = URL.createObjectURL(this.imageProfilSelected);
    // },
  },

}
</script>
