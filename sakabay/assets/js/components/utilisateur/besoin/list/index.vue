<template>
  <div class="container-fluid skb-body p-4 dashboard">
    <div v-show="loading">
      <div class="loader-container-full">
        <div class="loader" />
      </div>
    </div>
    <div class="row justify-content-between pt-4 mb-4">
      <div class="col-3 align-self-center">
        <font-awesome-icon
          class="grey-skb fontSize24 mr-2"
          :icon="['fas', 'edit']"
        />
        <h1 class="text-center fontPoppins fontSize20 dashboard-title">
          {{ $t('besoin.title_list') }}
        </h1>
      </div>
      <div
        v-if="companies.length > 0"
        class="col-3 mb-2"
      >
        <fieldset
          id="entitySelected"
          class="entitySelected"
        >
          <multiselect
            ref="multiselect"
            v-model="entitySelected"
            :placeholder="$t('besoin.placeholder.select')"
            :disabled="companies.length < 0"
            :options="companies"
            name="entitySelected"
            :searchable="false"
            :close-on-select="true"
            :show-labels="false"
            :custom-label="$getAuthorLabel"
            :allow-empty="false"
            track-by="name"
            @select="onSelectEntity"
          />
        </fieldset>
      </div>
      <div class="col-3 align-self-center">
        <a
          href="/services/new"
          class="button_skb_blue btn py-2"
        >
          <i class="mr-2 fas fa-plus-circle" />
          {{ $t('besoin.create_button') }}
        </a>
      </div>
    </div>
    <div class="row">
      <!-- Demandes en cours -->
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <div class="title-card-skb ">
                  <span class="underline">{{ $t('besoin.pending_request') }}</span>
                  <span
                    class="fontPoppins fontSize12 py-1 px-2 orange-gradiant white-skb rounded"
                  >{{ nbResultPending }}</span>
                </div>
              </div>
            </div>

            <div
              class="row scroll-h500"
            >
              <vuescroll>
                <div class="col-12">
                  <div
                    v-for="(pendingBesoin, index) in pendingBesoins"
                    :key="'pendingBesoin_' + index"
                    class="service-card mb-2 px-3 pt-3 border"
                  >
                    <pending-besoin
                      :pending-besoin="pendingBesoin"
                      :loading="loadingModal"
                      @delete-modal-opened="onDeletedPendingService(index, pendingBesoin.id)"
                      @request-quote-modal-opened="event => onResquestQuotePending(index, pendingBesoin, event)"
                    />
                  </div>
                  <div
                    class="w-100 whitebg text-center"
                  >
                    <div
                      v-if="isScrolling"
                      v-show="loading2"
                      class="my-5"
                    >
                      <div class="loader4" />
                    </div>
                  </div>

                  <div
                    v-if="pendingBesoins.length"
                    v-observe-visibility="(isVisible,entry) => throttledScroll(isVisible,API_URL, paramsPending, entry, 'pendingBesoins')"
                    name="spy"
                  />
                  <div
                    v-if="bottom && pendingBesoins.length > 0"
                    class="text-center pt-4"
                  >
                    <span>{{ $t('opportunity.customer.end_of_results') }}</span>
                  </div>
                  <div
                    v-else-if="bottom && pendingBesoins.length === 0"
                    class="text-center pt-4"
                  >
                    <span>{{ $t('besoin.no_pending_services') }}</span>
                  </div>
                </div>
              </vuescroll>
            </div>
          </div>
        </div>
      </div>
      <!-- Demandes expirées -->
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <div class="title-card-skb">
                  {{ $t('besoin.expired_request') }}
                  <span
                    class="fontPoppins fontSize12 py-1 px-2 orange-gradiant white-skb rounded"
                  >{{ nbResultExpired }}</span>
                </div>
              </div>
            </div>
            <div
              class="row scroll-h500"
            >
              <vuescroll :ops="opsButton">
                <div class="col">
                  <div
                    v-for="(expiredBesoin, index) in expiredBesoins"
                    :key="'expiredBesoin_' + index"
                    class="service-card mb-2 px-3 pt-3 border"
                  >
                    <expired-besoin
                      :expired-besoin="expiredBesoin"
                      @delete-modal-opened="onDeletedExpiredService(index, expiredBesoin.id)"
                    />
                  </div>
                  <div
                    class="w-100 whitebg text-center"
                  >
                    <div
                      v-if="isScrolling2"
                      v-show="loading3"
                      class="my-5"
                    >
                      <div class="loader4" />
                    </div>
                  </div>

                  <div
                    v-if="expiredBesoins.length"
                    v-observe-visibility="(isVisible,entry) => throttledScroll(isVisible, API_URL, paramsExpired, entry, 'expiredBesoins')"
                    name="spy"
                  />
                  <div
                    v-if="bottom2 && expiredBesoins.length > 0"
                    class="text-center pt-4"
                  >
                    <span>{{ $t('opportunity.customer.end_of_results') }}</span>
                  </div>
                  <div
                    v-else-if="bottom2 && expiredBesoins.length === 0"
                    class="text-center pt-4"
                  >
                    <span>{{ $t('besoin.no_expired_services') }}</span>
                  </div>
                </div>
              </vuescroll>
            </div>
          </div>
        </div>
      </div>
    </div>
    <confirm-modal
      :id="DELETE_CONFIRM_MODAL_ID"
      :title-text="$t('commons.confirm_modal.delete.title')"
      :body-text="$t('commons.confirm_modal.delete.text')"
      :button-yes-text="$t('commons.yes')"
      :button-no-text="$t('commons.no')"
      :are-buttons-on-same-line="true"
      @confirm-modal-yes="deleteRequest()"
    />
    <confirm-modal
      :id="REQUEST_QUOTE_MODAL_ID"
      :title-text="$t('besoin.modal_request_quote.title')"
      :body-text="$t('besoin.modal_request_quote.text', [currentPendingBesoinTitle, currentCompanyName])"
      :nl2br="true"
      :button-yes-text="$t('commons.yes')"
      :button-no-text="$t('commons.no')"
      :are-buttons-on-same-line="true"
      @confirm-modal-yes="submitRequestQuote()"
    />
  </div>
</template>
<script>
  import axios from 'axios';
  import vuescroll from 'vuescroll';
  import PendingBesoin from './pending-besoin-item.vue';
  import ExpiredBesoin from './expired-besoin-item.vue';
  import _ from 'lodash';
  import ConfirmModal from 'components/commons/confirm-modal';
  import searchAndScrollMixin from 'mixins/searchAndScrollMixin';

  export default {
    components: {
      vuescroll,
      PendingBesoin,
      ExpiredBesoin,
      ConfirmModal
    },
    mixins: [searchAndScrollMixin],

    props: {
      utilisateurId: {
        type: Number,
        default: null
      }
    },
    data() {
      return {
        API_URL: '/api/besoins/utilisateur/' + this.utilisateurId,
        REQUEST_QUOTE_MODAL_ID: 'request_quoteModal',
        DELETE_CONFIRM_MODAL_ID: 'delete_confirmModal',
        loading: true,
        loading2: false,
        loading3: false,
        loadingModal: false,
        currentPendingId: null,
        indexPending: null,
        currentExpiredId: null,
        indexExpired: null,
        currentPendingBesoinTitle: null,
        currentCompanyName: null,
        currentAnswerId: null,
        indexAnswer: null,
        pendingBesoins: [],
        expiredBesoins: [],
        opsButton: {
          bar: {
            keepShow: true,
            minSize: 0.3,
          },
          rail: {
            background: '#c5c9cc',
            opacity: 0.4,
            size: '6px',
            specifyBorderRadius: false,
            gutterOfEnds: '1px',
            gutterOfSide: '2px',
            keepShow: false
          },
        },
        isScrolling: false,
        isScrolling2: false,
        bottom: false,
        bottom2: false,
        nbResultPending: 0,
        nbResultExpired: 0,
        nbMaxResult: 10,
        currentPage: 2,
        currentPage2: 2,
        firstAttempt: true,
        companies: [],
        utilisateur: null,
        entitySelected: null,
        entityId: ''
      };
    },
    computed: {
      paramsPending() {
        let params = {};
        params.codeStatut = 'PUB';
        params.currentPage = this.currentPage;
        return params;
      },
      paramsExpired() {
        let params = {};
        params.codeStatut = 'ENC';
        params.currentPage = this.currentPage2;
        return params;
      },
    },
    created() {
      let promises = [];
      promises.push(axios.get('/api/companies/utilisateur/' + this.utilisateurId));
      return Promise.all(promises).then(res => {
        this.companies = _.cloneDeep(res[0].data);
        if(this.companies.length > 0) {
          this.utilisateur = _.cloneDeep(this.companies[0].utilisateur);
          this.companies.push(this.utilisateur);
          this.entitySelected = this.utilisateur;
        }
        this.getBesoins();
      }).catch(e => {
        this.$handleError(e);
        this.loading = false;
      });
    },
    methods: {
      onDeletedPendingService(index, pendingBesoinId) {
        this.currentPendingId = pendingBesoinId;
        this.indexPending = index;
      },
      onDeletedExpiredcService(index, expiredBesoinId) {
        this.currentExpiredId = expiredBesoinId;
        this.indexExpired = index;

      },
      onResquestQuotePending(index, pendingBesoin, event) {
        this.indexPending = index;
        this.currentPendingBesoinTitle = pendingBesoin.title;
        this.currentCompanyName = event.company_name;
        this.currentAnswerId = event.answer_id;
        this.indexAnswer = event.answer_index;
      },
      deleteRequest() {
        axios.delete(this.currentPendingId ? '/api/besoins/' + this.currentPendingId : '/api/besoins/' + this.currentExpiredId)
          .then(res => {
            if (this.currentPendingId) {
              this.pendingBesoins.splice(this.indexPending, 1);
            }
            if (this.currentExpiredId) {
              this.expiredBesoins.splice(this.indexExpired, 1);
            }
            this.currentExpiredId = null;
            this.currentPendingId = null;
            this.indexPending = null;
            this.indexExpired = null;
          })
          .catch(e => {
            this.$handleError(e);
          });
      },
      submitRequestQuote() {
        this.loadingModal = true;
        axios.post('/api/answers/quote/' + this.currentAnswerId)
          .then(res => {
            this.pendingBesoins[this.indexPending].answers[this.indexAnswer].request_quote = true;
            this.indexPending = null;
            this.indexAnswer = null;
            this.loadingModal = false;

          }).catch(e => {
            this.loadingModal = false;

          });
      },
      getBesoins() {
        let promises = [];
        promises.push(axios.get(this.API_URL,{
          params: {
            codeStatut: 'PUB',
            company: this.entityId
          }
        }
        ));
        promises.push(axios.get(this.API_URL,{
          params: {
            codeStatut: 'ENC',
            company: this.entityId
          }
        }
        ));

        if (this.firstAttempt) {
          promises.push(axios.get(this.API_URL, {
            params: {
              codeStatut: 'PUB',
              count: true,
              company: this.entityId
            }
          }));
          promises.push(axios.get(this.API_URL,  {
            params: {
              codeStatut: 'ENC',
              count: true,
              company: this.entityId
            }
          }));
        }
        return Promise.all(promises).then(res => {
          this.pendingBesoins = _.cloneDeep(res[0].data);
          this.expiredBesoins = _.cloneDeep(res[1].data);

          if (this.pendingBesoins.length < this.nbMaxResult) {
            this.bottom = true;
          }
          if (this.expiredBesoins.length < this.nbMaxResult) {
            this.bottom2 = true;
          }
          if (this.firstAttempt) {
            this.nbResultPending = _.cloneDeep(res[2].data);
            this.nbResultExpired = _.cloneDeep(res[3].data);
            this.firstAttempt = false;
          }
          this.loading = false;
        }).catch(e => {
          this.$handleError(e);
          this.loading = false;
        });
      },
      onSelectEntity() {
        this.$nextTick(() => {
          if (this.entitySelected) {
            if (this.utilisateur && !_.isEqual(this.entitySelected, this.utilisateur)) {
              this.entityId = this.entitySelected.id;
            } else {
              this.entityId = '';
            }
          }
          this.resetSearch();
          this.getBesoins();
        });
      }
    },

  };
</script>
