<template>
  <div class="container-fluid skb-body p-4 dashboard">
    <div v-show="loading">
      <div class="loader-container-full">
        <div class="loader" />
      </div>
    </div>
    <div class="row justify-content-between pt-4 mb-4">
      <div class="col-4 align-self-center">
        <i class="fas fa-edit grey-skb fontSize20 mr-2" />
        <h1 class="text-center fontPoppins fontSize20 dashboard-title">
          {{ $t('besoin.title_list') }}
        </h1>
      </div>
      <div class="col-2 align-self-center">
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
                  >{{ pendingBesoins.length }}</span>
                </div>
              </div>
            </div>
            <div v-if="pendingBesoins.length == 0">
              <div class="row">
                <div class="col">
                  <p class="text-center mt-3">
                    {{ $t('besoin.no_pending_services') }}
                  </p>
                </div>
              </div>
            </div>
            <div
              v-else
              class="row scroll-h500"
            >
              <vuescroll :ops="opsButton">
                <div class="col">
                  <div
                    v-for="(pendingBesoin, index) in pendingBesoins"
                    :key="'pendingBesoin_' + index"
                    class="service-card mb-2 p-3 border"
                  >
                    <pending-besoin
                      :pending-besoin="pendingBesoin"
                      @service-deleted="onDeletedPendingService(index, pendingBesoin.id)"
                    />
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
                  >{{ expiredBesoins.length }}</span>
                </div>
              </div>
            </div>
            <div v-if="expiredBesoins.length == 0">
              <div class="row">
                <div class="col">
                  <p class="text-center mt-3">
                    {{ $t('besoin.no_expired_services') }}
                  </p>
                </div>
              </div>
            </div>
            <div
              v-else
              class="row scroll-h300"
            >
              <vuescroll :ops="opsButton">
                <div class="col">
                  <div
                    v-for="(expiredBesoin, index) in expiredBesoins"
                    :key="'expiredBesoin_' + index"
                    class="service-card mb-2 p-3 border"
                  >
                    <expired-besoin
                      :expired-besoin="expiredBesoin"
                      @service-deleted="onDeletedExpiredService(index, expiredBesoin.id)"
                    />
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
  </div>
</template>
<script>
  import axios from 'axios';
  import vuescroll from 'vuescroll';
  import PendingBesoin from './pending-besoin-item.vue';
  import ExpiredBesoin from './expired-besoin-item.vue';
  import _ from 'lodash';
  import ConfirmModal from 'components/commons/confirm-modal';

  export default {
    components: {
      vuescroll,
      PendingBesoin,
      ExpiredBesoin,
      ConfirmModal
    },
    props: {
      utilisateurId: {
        type: Number,
        default: null
      }
    },
    data() {
      return {
        DELETE_CONFIRM_MODAL_ID: 'delete_confirmModal',
        loading: true,
        currentPendingId: null,
        indexPending: null,
        indexExpired: null,
        currentExpiredId: null,
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
      };
    },
    created() {
      let promises = [];
      promises.push(axios.get('/api/besoins/utilisateur/' + this.utilisateurId,{
        params: {
          codeStatut: 'PUB'
        }
      }
      ));
      promises.push(axios.get('/api/besoins/utilisateur/' + this.utilisateurId,{
        params: {
          codeStatut: 'ENC'
        }
      }
      ));
      return Promise.all(promises).then(res => {
        this.loading = false;
        this.pendingBesoins = _.cloneDeep(res[0].data);
        this.expiredBesoins = _.cloneDeep(res[1].data);
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
      deleteRequest() {
        console.log(this.currentPendingId ? '/api/besoins/' + this.currentPendingId : '/api/besoins/' + this.currentExpiredId);
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
            console.log('non');

            this.$handleError(e);
          });
      }
    },

  };
</script>
