<template>
  <div class="container skb-body">
    <div v-if="loading">
      <div class="loader-container-full">
        <div class="loader" />
      </div>
    </div>

    <div
      v-else
      class="row"
    >
      <b-card-group deck>
        <b-card
          v-for="(subscription , index) in subscriptions "
          :key="'subscription_'+index"
          :title="subscription.name"
          img-src="build/premium.jpg"
          img-alt="Image"
          img-top
        >
          <b-card-text v-if="subscription.name.toLowerCase() == 'premium'">
            {{ $t('subscription.detailsPremium') }}
          </b-card-text>
          <b-card-text v-if="subscription.name.toLowerCase() == 'pro'">
            {{ $t('subscription.detailsPro') }}
          </b-card-text>
          <b-card-text v-if="subscription.name.toLowerCase() == 'free'">
            {{ $t('subscription.detailsFree') }}
          </b-card-text>
          <a
            class="btn button_skb_yellow"
            :href="'subscription/'+subscription.name.toLowerCase()"
          >
            Follow
          </a>
        </b-card>
      </b-card-group>
    </div>
  </div>
</template>

<script>

  import axios from 'axios';

  export default {
    props: {

      subscriptionId: {
        type: Number,
        default: null
      },
    },
    data() {

      return {
        loading: true,
        subscriptions: [],
        currentName: null,
      };
    },
    created() {
      this.getAllSubscriptions();

    }, methods: {
      getAllSubscriptions() {
        this.loading = true;
        return axios.get('/api/subscribes')
          .then(response => {
            this.subscriptions = response.data;
            this.loading = false;
          }).catch(error => {
            this.$handleError(error);
            this.loading = false;
          });

      },
    },
  };
</script>


<style>
</style>
