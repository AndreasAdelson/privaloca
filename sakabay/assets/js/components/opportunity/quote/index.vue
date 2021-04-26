<template>
  <div>
    <div v-show="loading">
      <div class="loader-container-full">
        <div class="loader" />
      </div>
    </div>
    <div class="row justify-content-between mb-4">
      <div class="col-4 align-self-center">
        <font-awesome-icon
          class="grey-skb fontSize24 mr-2"
          :icon="['fas', 'edit']"
        />
        <h1 class="text-center fontPoppins fontSize20 dashboard-title">
          {{ $t('opportunity.quote.title') }} <span class="fontPoppins fontSize12 py-1 px-2 orange-gradiant white-skb rounded">{{ nbResult }}</span>
        </h1>
      </div>
    </div>
    <div class="opportunity-list">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <div class="title-card-skb ">
                    <span class="underline">{{ $t('opportunity.quote.individual_request') }}</span>
                    <span
                      class="fontPoppins fontSize12 py-1 px-2 orange-gradiant white-skb rounded"
                    >{{ nbResult }}</span>
                  </div>
                </div>
              </div>
              <div v-if="printedEntities.length == 0">
                <div class="row">
                  <div class="col">
                    <p class="text-center mt-3">
                      {{ $t('opportunity.quote.no_individual_request') }}
                    </p>
                  </div>
                </div>
              </div>
              <div
                v-else
                class="row scroll-h500"
              >
                <vuescroll>
                  <div class="col-12">
                    <div
                      v-for="(pendingQuote, index) in printedEntities"
                      :key="'pendingQuote_' + index"
                      :class="pendingQuote.answers[0].quote ? '' : 'yellow_on_waiting'"
                      class="service-card mb-2 px-3 pt-3 border"
                    >
                      <quote-card
                        :pending-quote="pendingQuote"
                        :company-selected="companySelected"
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
                      v-if="printedEntities.length"
                      v-observe-visibility="(isVisible,entry) => throttledScroll(isVisible,API_URL,params,entry, 'quote')"
                      name="spy"
                    />
                    <div
                      v-if="bottom && printedEntities.length > 0"
                      class="text-center pt-4"
                    >
                      <span>{{ $t('opportunity.customer.end_of_results') }}</span>
                    </div>
                    <div
                      v-else-if="bottom && printedEntities.length === 0"
                      class="text-center pt-4"
                    >
                      <span>{{ $t('opportunity.customer.no_opportunity') }}</span>
                    </div>
                  </div>
                </vuescroll>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import searchAndScrollMixin from 'mixins/searchAndScrollMixin';
  import axios from 'axios';
  import _ from 'lodash';
  import vuescroll from 'vuescroll';
  import QuoteCard from './quote-card.vue';

  export default {
    components:{
      vuescroll,
      QuoteCard
    },
    mixins: [searchAndScrollMixin],
    props: {
      companySelected: {
        type: Object,
        default: null
      }
    },
    data() {
      return {
        API_URL: '/api/opportunities/quote',
        loading: false,
        loading2: false,
        nbResult: 0,
        isScrolling: false,
        bottom: false,
        nbMaxResult: 10,
        currentPage: 2,
        printedEntities: [],
        firstAttempt: true,
      };
    },
    computed: {
      params() {
        let params = {};
        params.company = this.companySelected.id;
        params.currentPage = this.currentPage;
        return params;
      }
    },
    watch: {
      companySelected(newValue) {
        this.resetSearch();
        this.getOpportunities();
      }
    },
    methods: {
      getOpportunities() {
        let promises = [];
        this.loading = true;
        promises.push(axios.get('/api/opportunities/quote', {
          params: {
            company: this.companySelected.id
          }
        }));
        if (this.firstAttempt) {
          promises.push(axios.get('/api/opportunities/quote?count=true', {
            params: {
              company: this.companySelected.id
            }
          }));
        }
        return Promise.all(promises).then(res => {
          this.loading = false;
          this.printedEntities = _.cloneDeep(res[0].data);
          if (this.printedEntities.length < this.nbMaxResult) {
            this.bottom = true;
          }
          if (this.firstAttempt) {
            this.nbResult = _.cloneDeep(res[1].data);
            this.firstAttempt = false;
          }
        }).catch(e => {
          this.$handleError(e);
          this.loading = false;
        });
      },
    },
  };
</script>
