<template>
  <div class="container-fluid skb-body">
    <div v-show="loading">
      <div class="loader-container-full">
        <div class="loader" />
      </div>
    </div>
    <div class="row my-4">
      <div class="col-4">
        <h1 class="fontUbuntuItalic orange-skb">
          {{ this.$t('besoin_statut.title') }}
        </h1>
      </div>
      <div class="col-1" />
      <div class="col-5">
        <b-form-group
          horizontal
          class="mb-0"
        >
          <b-input-group>
            <b-form-input
              v-model="currentFilter"
              :placeholder="$t('commons.rechercher')"
              @keydown.enter.native="applyFilter()"
            />
            <b-input-group-append>
              <b-btn
                :disabled="!currentFilter"
                @click="applyFilter()"
              >
                <i class="fas fa-search" />
              </b-btn>
            </b-input-group-append>
          </b-input-group>
        </b-form-group>
      </div>
      <div
        v-if="canCreate"
        class="col-1"
      >
        <a href="/admin/besoin-statut/new">
          <b-button class="button_skb">{{ this.$t('commons.create') }}</b-button>
        </a>
      </div>
    </div>
    <!-- Table -->
    <b-row>
      <b-col cols="12">
        <b-table
          ref="table"
          class="tablestyle"
          :items="refreshData"
          :fields="table.field"
          :current-page="pager.currentPage"
          :per-page="pager.perPage"
          :busy.sync="table.isBusy"
          :filter="table.filter"
          :sort-by.sync="table.sortBy"
          :sort-desc.sync="table.sortDesc"
          small
          bordered
          responsive
          fixed
        >
          <template #cell(actions)="data">
            <b-button-group>
              <a
                v-if="canRead"
                :href="'/admin/besoin-statut/show/' + data.value "
              >
                <b-button><i class="fas fa-eye" /></b-button>
              </a>
              <a
                v-if="canEdit"
                :href="'/admin/besoin-statut/edit/' + data.value "
                class="mx-1"
              >
                <b-button><i class="fas fa-edit" /></b-button>
              </a>
              <b-button
                v-if="canDelete"
                data-toggle="modal"
                :data-target="'#' + DELETE_CONFIRM_MODAL_ID"
                @click="currentId = data.value"
              >
                <i class="fas fa-trash" />
              </b-button>
            </b-button-group>
          </template>
          <template #cell(fonctions)="data">
            <div v-html="data.value" />
          </template>
        </b-table>
      </b-col>
    </b-row>
    <b-row align-h="center">
      <b-col cols="12">
        <b-pagination
          v-model="pager.currentPage"
          :total-rows="pager.totalRows"
          :per-page="pager.perPage"
          align="center"
        />
      </b-col>
    </b-row>
    <confirm-modal
      :id="DELETE_CONFIRM_MODAL_ID"
      :title-text="$t('commons.confirm_modal.delete.title')"
      :body-text="$t('commons.confirm_modal.delete.text')"
      :button-yes-text="$t('commons.yes')"
      :button-no-text="$t('commons.no')"
      :are-buttons-on-same-line="true"
      @confirm-modal-yes="$deleteEntity('/api/admin/besoin-statuts/')"
    />
  </div>
</template>

<script>
  import axios from 'axios';
  import paginationMixin from 'mixins/paginationMixin';
  import ConfirmModal from 'components/commons/confirm-modal';
  import _ from 'lodash';
  export default {
    components: {
      ConfirmModal
    },
    mixins: [paginationMixin],
    props: {
      canCreate: {
        type: Boolean,
        default: false
      },
      canEdit: {
        type: Boolean,
        default: false
      },
      canRead: {
        type: Boolean,
        default: false
      },
      canDelete: {
        type: Boolean,
        default: false
      },
    },
    data() {
      return {
        DELETE_CONFIRM_MODAL_ID: 'delete_confirmModal',
        currentId: null,
        currentFilter: '',
        table: {
          field: [
            { key: 'name', label: this.$t('besoin_statut.fields.name'), sortable: true, thClass: 'tableitem' },
            { key: 'code', label: this.$t('besoin_statut.fields.code'), sortable: true, thClass: 'tableitem' },
            (!this.canDelete && !this.canEdit && !this.canRead) ? null : { key: 'actions', label: this.$t('commons.actions'), class: 'col-size-12', thClass: 'tableitem' },
          ],
          sortBy: 'code'
        },
        loading: false
      };
    },
    methods: {
      refreshData() {
        this.loading = true;
        return axios.get('/api/admin/besoin-statuts', {
          params: {
            filterFields: 'name,code',
            filter: this.currentFilter,
            sortBy: this.table.sortBy,
            sortDesc: this.table.sortDesc,
            currentPage: this.pager.currentPage,
            perPage: this.pager.perPage
          }
        }).then(response => {
          let items = _.map(response.data, besoinStatut => _.assign(besoinStatut, {
            code: besoinStatut.code,
            name: besoinStatut.name,
            actions: besoinStatut.id,
          }));
          this.pager.totalRows = parseInt(response.headers['x-total-count']);
          this.loading = false;
          return items;
        }).catch(error => {
          this.$handleError(error);
          this.loading = false;
          return [];
        });
      },
    },
  };
</script>
