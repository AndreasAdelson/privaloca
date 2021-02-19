<template>
  <table class="table table-hover text-center">
    <thead>
      <tr class="fontAlice">
        <th scope="col">
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
      </tr>
    </thead>
    <tbody class="fontPoppins fontSize14">
      <tr
        v-for="(history, index) in printedHistory"
        :key="'history_'+ index"
        :class="history.isActive ? 'orange-skb' : ''"
      >
        <td>{{ history.company_name }}</td>
        <td>{{ history.subscription.name }}</td>
        <td>{{ history.subscription.price }}</td>
        <td>{{ getDtDebutLabel(history.dt_debut) }}</td>
        <td>{{ getDtDebutLabel(history.dt_fin) }}</td>
        <td>{{ history.isActive ? $t('dashboard.history.table.in_progress') : $t('dashboard.history.table.expired') }}</td>
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
            if (moment(history.dt_fin, 'DD/MM/YYYY HH:mm:ss').isAfter()) {
              history.isActive = true;
            } else {
              history.isActive = false;
            }
            return new Date(dtFin.toString());
          }
        ], ['desc']);
      }
    }
  };
</script>
