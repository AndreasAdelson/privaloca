<template>
  <div class="container-fluid skb-body p-4 dashboard">
    <div v-show="loading">
      <div class="loader-container-full">
        <div class="loader" />
      </div>
    </div>
    <div class="row justify-content-between pt-4 mb-4">
      <div class="col-4 align-self-center">
        <i class="far fa-copy grey-skb fontSize20 mr-2" />
        <h1 class="text-center fontPoppins fontSize20 dashboard-title">
          {{ $t('opportunity.title_list') }} <span class="fontPoppins fontSize12 py-1 px-2 orange-gradiant white-skb rounded">{{ nbResult }}</span>
        </h1>
      </div>
      <div class="col-3">
        <fieldset
          id="companySelected"
          class="companySelected"
        >
          <multiselect
            v-model="companySelected"
            :disabled="onlyOne"
            :options="companies"
            name="companySelected"
            :searchable="false"
            :close-on-select="true"
            :show-labels="false"
            label="name"
            track-by="name"
          />
        </fieldset>
      </div>
    </div>
    <div class="opportunity-list">
      <div class="row">
        <div class="col-12">
          <div class="container">
            <div class="row">
              <div class="col-12">
                <opportunity-card
                  v-for="(opportunity, index) in printedOpportunities"
                  :key="'opp_' + index"
                  :opportunity="opportunity"
                  class="row mb-2 card"
                />
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
                  v-if="printedOpportunities.length"
                  v-observe-visibility="(isVisible,entry) => throttledScroll(isVisible,entry)"
                  name="spy"
                />
                <div
                  v-if="bottom"
                  class="text-center mt-4"
                >
                  <span>Fin des résultats</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</template>
<script>
  import axios from 'axios';
  import _ from 'lodash';
  import OpportunityCard from './opportunity-card.vue';

  export default {
    components: {
      OpportunityCard
    },
    props: {
      utilisateurId: {
        type: Number,
        default: null
      }
    },
    data() {
      return {
        loading: true,
        loading2: false,
        firstAttempt: true,
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
        printedOpportunities: [],
        companies: [],
        companySelected: null,
        onlyOne: true,
        category: null,
        sousCategories: null,
        isScrolling: false,
        bottom: false,
        nbResult: 0,
        nbMaxResult: 10,
        currentPage: 1
      };
    },
    computed: {
      /**
       * Limits scroll function invokation to at most once per every 4 seconds.
       * To be used within visibility-changed callback.
       */
      throttledScroll() {
        return _.throttle(this.scroll, 2000);
      }
    },
    watch: {
      companySelected(newValue) {
        this.sousCategories = [];
        this.category =  _.clone(newValue.category.code);
        if (newValue.sous_categorys.length > 0) {
          this.sousCategories = _.clone(_.map(newValue.sous_categorys, 'code'));
        }
        this.resetSearch();
        this.getOpportunities();
      }
    },
    created() {
      let promises = [];
      promises.push(axios.get('/api/companies/utilisateur/' + this.utilisateurId));
      return Promise.all(promises).then(res => {
        this.loading = false;
        this.companies = _.cloneDeep(res[0].data);
        if (this.companies.length > 0) {
          this.companySelected = this.companies[0];
          if (this.companies.length > 1) {
            this.onlyOne =  false;
          }
        }
      }).catch(e => {
        this.$handleError(e);
        this.loading = false;
      });
    },
    methods: {
      getOpportunities() {
        let promises = [];
        this.loading = true;
        promises.push(axios.get('/api/opportunities', {
          params: {
            category: this.category,
            sousCategory: this.sousCategories,
          }
        }));
        if (this.firstAttempt) {
          promises.push(axios.get('/api/opportunities?count=true', {
            params: {
              category: this.category,
              sousCategory: this.sousCategories
            }
          }));
        }
        return Promise.all(promises).then(res => {
          this.loading = false;
          this.printedOpportunities = _.cloneDeep(res[0].data);
          if (this.printedOpportunities.length < this.nbMaxResult) {
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

      /**
       * Handler for the scroll observation.
       * Must be throttled for use. See computed properties.
       * @param {Boolean} isVisible
       * @param {*} entry
       */
      scroll(isVisible, entry) {
        if(!this.bottom) {
          if (isVisible && !this.isScrolling) {
            this.isScrolling = true;
            this.loading2 = true;
            this.currentPage++;
            return axios.get('/api/opportunities', {
              params: {
                category: this.category,
                sousCategory: this.sousCategories,
                currentPage: this.currentPage
              }
            }).then(res => {
              this.loading2 = false;
              this.isScrolling = false;
              console.log(res.data.length > 0);
              if (res.data.length > 0) {
                this.printedOpportunities = _.unionBy(this.printedOpportunities, res.data, 'id');
                if (res.data.length < this.nbMaxResult) this.bottom = true;
              }
              else {
                // If the request yielded no data, then we have reached the bottom of the list
                this.bottom = true;
              }
            }).catch(e => {
              this.$handleError(e);
              this.loading2 = false;
            });
          }
        }
      },

      resetSearch() {
        this.firstAttempt = true;
        this.loading = true;
        this.printedOpportunities = [];
        this.bottom = false;
        this.currentPage = 1;
      },
    }

  };
</script>
