<template>
  <div class="container skb-body">
    <div v-if="loading">
      <div class="loader-container-full">
        <div class="loader" />
      </div>
    </div>

    <div
      v-else
      class="row w-50 px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center"
    >
      <div class="card-deck col-md-12">
        <div
          v-if="formFields.subscription"
          class="card mb-2 "
        >
          <div class="card-header">
            <h4 class="my-0 font-weight-normal">
              {{ formFields.subscription.name }}
            </h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title">
              {{ formFields.subscription.price }} €<small class="text-muted">/ mois</small>
            </h1>
            <ul class="list-unstyled mt-3 mb-4">
              <li>data</li>
              <li>data</li>
              <li>data</li>
              <li>data</li>
            </ul>
            <button
              data-toggle="modal"
              :data-target="'#' + CONFIRM_SUBSCRIPTION"
              type="submit"
              class="btn btn-lg btn-block btn-primary"
              :disabled="verifySubscription"
            >
              S'abonner
            </button>
          </div>
        </div>
      </div>
    </div>
    <confirm-modal
      :id="CONFIRM_SUBSCRIPTION"
      :title-text="$t('commons.confirm_modal.subscription_validate.validate.title')"
      :body-text="$t('commons.confirm_modal.subscription_validate.validate.text')"
      :button-yes-text="$t('commons.yes')"
      :button-no-text="$t('commons.no')"
      :are-buttons-on-same-line="true"
      @confirm-modal-yes="subscribe()"
    />
  </div>
</template>

<script>

  import axios from 'axios';
  import moment from 'moment';
  import ConfirmModal from 'components/commons/confirm-modal';

  export default {
    components: {
      ConfirmModal
    },
    props: {
      utilisateurId: {
        type: Number,
        default: null
      },
      subscriptionName: {
        type: String,
        default: null
      },
    },

    data() {

      return {
        CONFIRM_SUBSCRIPTION: 'confirm_subscription_modal',
        loading: true,
        API_URL: '/api/subscribes',
        formFields: {
          dtDebut: moment().format('YYYY/MM/DD H:m:s'),
          dtFin: moment().format('YYYY/MM/DD H:m:s'),
          company: new Object(),
          subscription: new Object()
        },
        formErrors: {
          dtDebut: [],
          dtFin: [],
          company: [],
          subscription: [],
        },

      };
    },
    computed: {
      verifySubscription() {
        let companySubscriptions = this.formFields.company.company_subscriptions;
        if (!this.loading) {
          if (this.formFields.company && companySubscriptions) {
            let subscription = companySubscriptions.find((item) => {
              return moment(item.dt_fin, 'DD/MM/YYYY HH:mm:ss').isAfter();
            });
            if(subscription) {
              return true;
            }
            else {
              return false;
            }
          }
        }
        return true;
      },
    },
    created() {
      this.getSubcriptionName();
      this.getUtilisateurCompany();
    },
    methods: {
      getSubcriptionName() {
        if (this.subscriptionName) {
          this.loading = true;
          return axios.get('/api/subscribes/' + this.subscriptionName)
            .then(response => {
              this.formFields.subscription = response.data;
              this.loading = false;
            }).catch(error => {
              this.$handleError(error);
            });
        }
      },
      getUtilisateurCompany() {
        if (this.utilisateurId) {
          this.loading = true;
          return axios.get('/api/companies/utilisateur/' + this.utilisateurId)
            .then(response => {
              this.formFields.company = response.data[0];
              this.loading = false;
            }).catch(error => {
              this.$handleError(error);
              this.loading = false;
            });
        }
      },
      subscribe() {
        let currentDate = moment(this.formFields.dtDebut);
        let futureMonth = moment(currentDate).add(1, 'M');
        futureMonth = futureMonth.add(1, 'd');
        this.formFields.dtFin = futureMonth.format('YYYY/MM/DD H:m:s');

        let formData = this.$getFormFieldsData(this.formFields);
        this.loading = true;
        return axios.post(this.API_URL, formData)
          .then(response => {
            this.loading = true;
            window.location.assign(response.headers.location);
          }).catch(error => {
            this.$handleError(error);
            this.loading = false;
          });
      },
    }
  };
</script>
