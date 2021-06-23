<template>
  <table class="table table-hover text-center mb-0">
    <thead>
      <tr class="fontAlice">
        <th
          v-if="withCompanyName"
          scope="col"
        >
          {{ $t('dashboard.history.table.company') }}
        </th>
        <th scope="col">
          {{ $t('dashboard.history.table.name') }}
        </th>
        <th scope="col">
          {{ $t('dashboard.history.table.price') }}
        </th>
        <th scope="col">
          {{ $t('dashboard.history.table.dt_debut') }}
        </th>
        <th scope="col">
          {{ $t('dashboard.history.table.dt_fin') }}
        </th>
        <th scope="col">
          {{ $t('dashboard.history.table.statut') }}
        </th>
        <th
          v-if="canEdit"
          scope="col"
        >
          {{ $t('dashboard.history.table.action') }}
        </th>
      </tr>
    </thead>
    <tbody class="fontPoppins fontSize14">
      <tr
        v-for="(history, index) in printedHistory"
        :key="'history_'+ index"
        :class="history.isActive ? 'orange-skb' : ''"
      >
        <td
          v-if="withCompanyName"
        >
          {{ history.company_name }}
        </td>
        <td>{{ history.subscription.name }}</td>
        <td>{{ getPriceLabel(history) }}</td>
        <td>{{ getDtDebutLabel(history.dt_debut) }}</td>
        <td>{{ getDtDebutLabel(history.dt_fin) }}</td>
        <td>{{ isHistoryActive(history) }}</td>
        <td v-if="canEdit">
          <a
            v-if="canEdit"
            :href="'/admin/company-subscription/edit/' + history.id"
            class="mx-1"
          >
            <b-button><font-awesome-icon :icon="['fas', 'edit']" /></b-button>
          </a>
        </td>
      </tr>
    </tbody>
  </table>
</template>
<script>
  import moment from 'moment';
  import _ from 'lodash';

  export default {
    props: {
      companySubscriptions: {
        type: Array,
        default: null
      },
      withCompanyName: {
        type: Boolean,
        default: false
      },
      canEdit: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {
        printedHistory: []
      };
    },
    created() {
      this.sortHistory();
    },
    methods: {
      getDtDebutLabel($date) {
        return moment($date,'DD/MM/YYYY HH:mm:ss').format('[le] DD/MM/YYYY, [à] HH:mm');
      },
      sortHistory() {
        this.printedHistory = _.cloneDeep(this.companySubscriptions);
        this.printedHistory = _.orderBy(this.printedHistory, [
          function(history) {
            let dtFin = moment(history.dt_fin, 'DD/MM/YYYY HH:mm:ss').format('MM/DD/YYYY H:mm:ss');
            if (moment(history.dt_fin, 'DD/MM/YYYY HH:mm:ss').isAfter() && moment(history.dt_debut, 'DD/MM/YYYY HH:mm:ss').isBefore()) {
              history.isActive = true;
            } else {
              history.isActive = false;
            }
            return new Date(dtFin.toString());
          }
        ], ['desc']);
      },
      isHistoryActive(history) {
        let label = '';
        switch(history.subscription_status.code) {
        case 'VAL':
          if (history.isActive) {
            label = this.$t('dashboard.history.table.in_progress');
          } else {
            label = this.$t('dashboard.history.table.coming_soon');
          }
          break;
        case 'TER':
          label = this.$t('dashboard.history.table.ended');
          break;
        case 'ENC':
          label = this.$t('dashboard.history.table.in_progress');
          break;
        case 'ANN':
          if (history.isActive) {
            label = this.$t('dashboard.history.table.canceled_renewal');
          }
          else {
            if (moment(history.dt_fin, 'DD/MM/YYYY HH:mm:ss').isAfter()) {
              label = this.$t('dashboard.history.table.canceled');
            } else {
              label = this.$t('dashboard.history.table.ended');
            }
          }
          break;
        case 'OFF':
          if(history.isActive) {
            label = this.$t('dashboard.history.table.offer');
          } else {
            label = this.$t('dashboard.history.table.ended');
          }
          break;
        }
        return label;
      },
      getPriceLabel(history) {
        let label = '';
        if (history.subscription_status.code === 'OFF') {
          label = '0';
        }
        else {
          label = history.subscription.price;
        }
        return label;
      }
    }
  };
</script>
