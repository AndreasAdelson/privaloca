<template>
  <div class="container-fluid skb-body">
    <div class="row my-4">
      <div class="col-4">
        <h1 class="fontUbuntu orange-skb">{{ this.$t('admin.fonction.title') }}</h1>
      </div>
      <div class="col-1"></div>
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
        <a href="/admin/fonction/new">
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
              <b-button
                v-if="canDelete"
                @click="deleteFonction(data.value)"
              ><i class="fas fa-trash"></i></b-button>
            </b-button-group>
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

export default {
  mixins: [paginationMixin],
  props: {
    canCreate: {
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
      currentFilter: '',
      table: {
        field: [
          { key: 'description', label: this.$t('admin.fonction.fields.description'), sortable: true, thClass: "tableitem" },
          (!this.canDelete) ? null : { key: 'actions', label: this.$t('commons.actions'), class: 'col-size-4', thClass: "tableitem" },
        ],
        sortBy: 'description'
      }
    };
  },
  methods: {
    deleteFonction (fonctionId) {
      return axios.delete("/api/admin/fonctions/" + fonctionId)
        .then(response => {
          window.location.assign(response.headers.location);
        });
    },
    refreshData () {
      return axios.get("/api/admin/fonctions", {
        params: {
          filterFields: 'description',
          filter: this.currentFilter,
          sortBy: this.table.sortBy,
          sortDesc: this.table.sortDesc,
          currentPage: this.pager.currentPage,
          perPage: this.pager.perPage
        }
      }).then(response => {
        let items = _.map(response.data, fonction => _.assign(fonction, {
          description: fonction.description,
          actions: fonction.id,
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
