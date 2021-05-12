<template>
  <div class="skb-body container">
    <div v-if="loading">
      <div class="loader-container-full">
        <div class="loader" />
      </div>
    </div>
    <div v-else>
      <div
        v-if="canEdit"
        class="row mt-3 "
      >
        <div class="col-6">
          <h1 class="fontUbuntuItalic orange-skb">
            {{ this.$t('commons.detail') }}
          </h1>
        </div>
        <div class="col-6 justify-content-end">
          <a
            class="float-right"
            :href="'/admin/subscription/edit/' + subscriptionId"
          >
            <b-button class="button_skb">{{ this.$t('commons.edit') }}</b-button>
          </a>
        </div>
      </div>
      <a @click="goBack()">
        <button
          :title="$t('commons.go_back')"
          type="button"
          class="w-40px mt-4 p-0 rounded-circle btn-close btn"
        >
          <font-awesome-icon :icon="['fas', 'times']" />
        </button>
      </a>
      <div class="register-card mt-3 w-100 h-100">
        <div class="row">
          <div class="col-6">
            <span class="fontPatua fontSize20">{{ $t('subscription.fields.name') }}</span>
          </div>
          <div class="col-6">
            <span class="fontPatua fontSize20">{{ $t('subscription.fields.code') }}</span>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-6">
            <span class="fontHelveticaOblique fontSize18">{{ subscription.name.toUpperCase() }}</span>
          </div>
          <div class="col-6">
            <span class="fontHelveticaOblique fontSize18">{{ subscription.code }} </span>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <span class="fontPatua fontSize20">{{ $t('subscription.fields.advantages') }}</span>
          </div>
          <div class="col-6">
            <span class="fontPatua fontSize20">{{ $t('subscription.fields.price') }}</span>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-6">
            <ul>
              <li
                v-for="(advantage, index) in subscription.advantages"
                :key="'advantage_'+ index"
                class="fontHelveticaOblique fontSize18"
              >
                {{ advantage.message }}
              </li>
            </ul>
          </div>
          <div class="col-6">
            <span class="fontHelveticaOblique fontSize18">{{ subscription.price }} €</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import axios from 'axios';

  export default {
    components: {
    },
    props: {
      subscriptionId: {
        type: Number,
        default: null
      },
      canEdit: {
        type: Boolean,
        default: false
      },
      urlPrecedente: {
        type: String,
        default: null
      }
    },
    data() {
      return {
        subscription: null,
        loading: true
      };
    },
    async created() {
      if (this.subscriptionId) {
        return axios.get('/api/admin/subscriptions/' + this.subscriptionId)
          .then(response => {
            this.subscription = response.data;
            this.loading = false;
          }).catch(error => {
            this.$handleError(error);
            this.loading = false;
          });
      }
    },
    methods: {
      goBack() {
        this.$goTo(this.urlPrecedente);
      },
    },
  };
</script>>
