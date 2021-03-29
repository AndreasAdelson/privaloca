<template>
  <div class="skb-body container">
    <div v-show="loading">
      <div class="loader-container-full">
        <div class="loader" />
      </div>
    </div>
    <a href="/">
      <button
        title="Annulez "
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
                  id="name"
                  class="name"
                >
                  <label class="fontUbuntuItalic fontSize14">{{ this.$t('company.fields.name') }}</label>
                  <input
                    v-model="formFields.name"
                    v-validate="'required'"
                    type="text"
                    name="name"
                    class="form-control"
                    :placeholder="$t('company.placeholder.name')"
                  >
                  <div
                    v-for="errorText in formErrors.name"
                    :key="'name_' + errorText"
                  >
                    <span class="fontUbuntuItalic fontSize13 red-skb">{{ errorText }}</span>
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
                  <label class="fontUbuntuItalic fontSize14">{{ this.$t('company.fields.num_siret') }}</label>
                  <input
                    v-model="formFields.numSiret"
                    v-validate="'required'"
                    name="numSiret"
                    type="text"
                    class="form-control"
                    :placeholder="$t('company.placeholder.num_siret')"
                    onkeypress="return event.charCode === 0 || event.charCode === 47 || (event.charCode >= 48 && event.charCode <= 57)"
                  >
                  <div
                    v-for="errorText in formErrors.numSiret"
                    :key="'code_' + errorText"
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
                  id="postalAddress"
                  class="postalAddress"
                >
                  <label class="fontUbuntuItalic fontSize14">{{ this.$t('company.table.fields.address.postal_address') }}</label>
                  <input
                    v-model="formFields.address.postalAddress"
                    v-validate="'required'"
                    name="postalAddress"
                    type="text"
                    :maxlength="255"
                    class="form-control"
                    :placeholder="$t('company.placeholder.postal_address')"
                    @blur="() => {
                      if (formFields.address.postalCode && formFields.address.postalAddress && formFields.city) {
                        getLongLat();
                      }
                    }"
                  >
                  <div
                    v-for="errorText in formErrors.postalAddress"
                    :key="'code_' + errorText"
                  >
                    <span class="fontUbuntuItalic fontSize13 red-skb">{{ errorText }}</span>
                  </div>
                </fieldset>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <fieldset
                  id="postalCode"
                  class="postalCode"
                >
                  <label class="fontUbuntuItalic fontSize14">{{ this.$t('company.table.fields.address.postal_code') }}</label>
                  <input
                    v-model="formFields.address.postalCode"
                    v-validate="'required'"
                    type="text"
                    name="postalCode"
                    class="form-control"
                    :placeholder="$t('company.placeholder.postal_code')"
                    onkeypress="return event.charCode === 0 || event.charCode === 47 || (event.charCode >= 48 && event.charCode <= 57)"
                    @blur="() => {
                      if (formFields.address.postalCode && formFields.address.postalAddress && formFields.city) {
                        getLongLat();
                      }
                    }"
                  >
                  <div
                    v-for="errorText in formErrors.postalCode"
                    :key="'name_' + errorText"
                  >
                    <span class="fontUbuntuItalic fontSize13 red-skb">{{ errorText }}</span>
                  </div>
                </fieldset>
              </div>
            </div>
          </div>
          <!-- Map row -->
          <div v-if="loadingMap">
            <div class="loader3" />
          </div>
          <div
            v-else-if="formFields.address.latitude && formFields.address.longitude"
            class="row mb-3"
          >
            <div
              class="col-12"
              style="height:300px"
            >
              <v-map
                :zoom="16"
                :center="[formFields.address.latitude, formFields.address.longitude]"
                style="height:300px"
              >
                <v-marker
                  :draggable="true"
                  :lat-lng.sync="position"
                />
                <v-tile-layer url="http://{s}.tile.osm.org/{z}/{x}/{y}.png" />
              </v-map>
            </div>
            <div class="col-12 warning-message">
              <span class="fontUbuntuItalic fontSize16 red-skb"><font-awesome-icon
                class="mx-1"
                :icon="['fas', 'exclamation-triangle']"
              />{{ $t('company.table.fields.address.warning_message') }}</span>
            </div>
          </div>
          <!-- third row -->
          <div class="row mb-3">
            <div class="col-6">
              <fieldset
                id="city"
                class="city"
              >
                <label class="fontUbuntuItalic fontSize14">{{ this.$t('company.table.fields.city') }}</label>
                <autocomplete
                  ref="autocomplete"
                  v-model="formFields.city"
                  :required="true"
                  name="city"
                  :min="2"
                  :debounce="500"
                  :on-should-render-child="$getCityLabel"
                  :on-select="setCity"
                  :placeholder="$t('company.placeholder.city')"
                  param="autocomplete"
                  url="/api/admin/cities"
                  anchor="label"
                  :classes="{input: 'form-control'}"
                />
                <div
                  v-for="errorText in formErrors.city"
                  :key="'city_' + errorText"
                >
                  <span class="fontUbuntuItalic fontSize13 red-skb">{{ errorText }}</span>
                </div>
              </fieldset>
            </div>
            <div class="col-6">
              <fieldset
                id="category"
                class="category"
              >
                <label class="fontUbuntuItalic fontSize14">{{ this.$t('company.table.fields.category') }}</label>

                <multiselect
                  v-model="formFields.category"
                  v-validate="'required'"
                  :options="category"
                  name="category"
                  placeholder="Selectionner categorie"
                  :searchable="false"
                  :close-on-select="false"
                  :show-labels="false"
                  label="name"
                  track-by="name"
                  open-direction="below"
                />
                <div
                  v-for="errorText in formErrors.category"
                  :key="'category_' + errorText"
                >
                  <span class="fontUbuntuItalic fontSize13 red-skb">{{ errorText }}</span>
                </div>
              </fieldset>
            </div>
          </div>

          <div class="row my-3">
            <div class="col-6 offset-3">
              <button
                type="button"
                class="btn button_skb fontUbuntuItalic"
                @click="$validateForm()"
              >
                {{ this.$t('commons.create') }}
              </button>
            </div>
            <!-- <div class="col-3">
              <button
                type="button"
                class="btn button_skb fontUbuntuItalic"
                @click="test()"
              >Test</button>
            </div> -->
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
  import axios from 'axios';
  import Autocomplete from 'vue2-autocomplete-js';
  import validatorRulesMixin from 'mixins/validatorRulesMixin';
  import _ from 'lodash';
  import adminFormMixin from 'mixins/adminFormMixin';
  export default {
    components: {
      Autocomplete
    },
    mixins: [
      validatorRulesMixin,
      adminFormMixin
    ],
    props: {
      utilisateurId: {
        type: Number,
        default: null
      },
    },
    data() {
      return {
        loading: false,
        loadingMap: false,
        API_URL: '/api/companies',
        formFields: {
          name: null,
          numSiret: null,
          utilisateur: new Object(),
          address: new Object(),
          category: null,
          city: new Object()
        },
        position: {
          lng: null,
          lat: null
        },
        formErrors: {
          name: [],
          numSiret: [],
          firstName: [],
          lastName: [],
          email: [],
          imageProfil: [],
          category: [],
          city: [],
          postalAddress: [],
          postalCode: []
        },
        category: [],
        utilisateur: null
      };
    },
    created() {
      let promises = [];
      promises.push(axios.get('/api/admin/categories'));
      if(this.utilisateurId) {
        promises.push(axios.get('/api/admin/utilisateurs/' + this.utilisateurId));
      }
      return Promise.all(promises).then(res => {
        this.category = res[0].data;
        if (this.utilisateurId) {
          this.utilisateur = res[1].data;
        }
        this.loading = false;
      }).catch(e => {
        this.$handleError(e);
        this.loading = false;
      });
    },
    methods: {

      getLongLat() {
        let query = this.formFields.address.postalAddress + ' ' + this.formFields.city;
        let postalCode = this.formFields.address.postalCode;
        this.loadingMap = true;
        return axios.get('https://api-adresse.data.gouv.fr/search/', {
          params: {
            q: query,
            postcode: postalCode,
            limit: 10
          }
        }).then(response => {
          if (response.data.features) {
            this.$set(this.formFields.address, 'longitude', response.data.features[0].geometry.coordinates[0]);
            this.$set(this.formFields.address, 'latitude', response.data.features[0].geometry.coordinates[1]);
            this.position.lat = response.data.features[0].geometry.coordinates[1];
            this.position.lng = response.data.features[0].geometry.coordinates[0];
          }
          this.loadingMap = false;
        }).catch(e => {
          this.$handleError(e);
          this.loadingMap = false;
        });
      },

      setCity(city) {
        if (this.formFields.address.postalAddress && this.formFields.address.postalCode) {
          this.getLongLat();
        }
        this.formFields.city = city;
        this.$refs.autocomplete.setValue(city.name);
      },

      submitForm() {
        this.loading = true;
        if (this.position.lat !== this.formFields.address.latitude && this.position.ngt !== this.formFields.address.longitude) {
          this.formFields.address.longitude = this.position.lng.toFixed(6);
          this.formFields.address.latitude = this.position.lat.toFixed(6);
        }
        //Gestion lorsque le navigateur propose de compléter automatiquement. Ici on s'assure que la ville existe en base avant de la set.
        if(this.$refs.autocomplete.json.length > 0) {
          this.formFields.city = this.$refs.autocomplete.json[0];
        }
        this.formFields.utilisateur = _.cloneDeep(this.utilisateur);
        let formData = this.$getFormFieldsData(this.formFields);
        return axios.post(this.API_URL, formData)
          .then(response => {
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
