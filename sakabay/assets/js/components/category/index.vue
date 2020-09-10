<template>
  <div class="container-fluid skb-body">
    <div class="row my-4">
      <div class="col-4">
        <h1 class="fontUbuntu orange-skb">{{ this.$t('admin.role.title') }}</h1>
      </div>
      <div class="col-1">
      </div>
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
              ><i class="fas fa-search"></i></b-btn>
            </b-input-group-append>
          </b-input-group>
        </b-form-group>
      </div>
      <div
        class="col-1"
        v-if="canCreate"
      >
        <a href="/admin/role/new">
          <b-button class="button_skb">{{ this.$t('commons.create') }}</b-button>
        </a>
      </div>
    </div>
    <!-- Table -->
    <b-row>
      <b-col cols="12">
        <b-table
          class="tablestyle"
          ref="table"
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
          <template v-slot:cell(actions)="data">
            <b-button-group>
              <a
                :href="'/admin/role/show/' + data.value "
                v-if="canRead"
              >
                <b-button><i class="fas fa-eye"></i></b-button>
              </a>
              <a
                v-if="canEdit"
                :href="'/admin/role/edit/' + data.value "
                class="mx-1"
              >
                <b-button><i class="fas fa-edit"></i></b-button>
              </a>
              <b-button
                v-if="canDelete"
                @click="deleteFonction(data.value)"
              ><i class="fas fa-trash"></i></b-button>
            </b-button-group>
          </template>
          <template v-slot:cell(fonctions)="data">
            <div v-html="data.value"></div>
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
        ></b-pagination>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import axios from 'axios';
import paginationMixin from 'mixins/paginationMixin';
import _ from 'lodash';
export default {
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
  data () {
    return {
      NB_MAX_DISPLAYED: 5,
      currentFilter: '',
      table: {
        field: [
          { key: 'name', label: this.$t('admin.role.fields.name'), sortable: true, thClass: "tableitem" },
          { key: 'code', label: this.$t('admin.role.fields.code'), sortable: true, thClass: "tableitem" },
          { key: 'fonctions', label: this.$t('admin.role.fields.fonctions'), sortable: true, thClass: "tableitem" },
          (!this.canDelete & !this.canEdit & !this.canRead) ? null : { key: 'actions', label: this.$t('commons.actions'), class: 'col-size-9', thClass: "tableitem" },
        ],
        sortBy: 'code'
      }
    };
  },
  methods: {
    deleteFonction (roleId) {
      return axios.delete("/api/admin/roles/" + roleId)
        .then(response => {
          window.location.assign(response.headers.location);
        });
    },
    refreshData () {
      return axios.get("/api/admin/roles", {
        params: {
          filterFields: 'name,code',
          filter: this.currentFilter,
          sortBy: this.table.sortBy,
          sortDesc: this.table.sortDesc,
          currentPage: this.pager.currentPage,
          perPage: this.pager.perPage
        }
      }).then(response => {
        let items = _.map(response.data, role => _.assign(role, {
          code: role.code,
          name: role.name,
          fonctions: _.take(
            _.sortBy(
              _.map(role.fonctions, 'description'), (description) => description), this.NB_MAX_DISPLAYED)
            .join('<br />')
            + (role.fonctions.length > this.NB_MAX_DISPLAYED ? '<br />' + this.$tc('commons.et_plus', role.fonctions.length - this.NB_MAX_DISPLAYED) : ''),
          actions: role.id,
        }));
        this.pager.totalRows = parseInt(response.headers['x-total-count']);
        return items;
      }).catch(error => {
        console.log(error);
        return [];
      });
    },
  },
}
</script>
