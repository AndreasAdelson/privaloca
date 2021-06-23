import axios from 'axios';
import _ from 'lodash';

export default {
  data() {
    return {

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
  methods: {
      /**
       * Handler for the scroll observation.
       * Must be throttled for use. See computed properties.
       * @param {Boolean} isVisible
       * @param {*} entry
       */
      scroll(isVisible, url, params, entry, page) {
        if (this.nbResult <= this.nbMaxResult) {
          this.bottom = true;
        }
        if (this.nbResult2 <= this.nbMaxResult) {
          this.bottom2 = true;
        }
        if(!this.bottom) {
          if (isVisible && !this.isScrolling) {
            this.isScrolling = true;
            this.loading2 = true;
            this.currentPage++;
            return axios.get(url, {params}).then(res => {
              this.isScrolling = false;
              if(page === 'customer') {
                if (res.data.length > 0) {
                  this.printedEntities = _.unionBy(this.printedEntities, this.checkAnswer(res.data), 'id');
                  if (res.data.length < this.nbMaxResult) this.bottom = true;
                }
                else {
                  // If the request yielded no data, then we have reached the bottom of the list
                  this.bottom = true;
                }
              } else if (page === 'company') {
                if (res.data.length > 0) {
                  this.printedEntities = _.unionBy(this.printedEntities, res.data, 'id');
                  if (res.data.length < this.nbMaxResult) this.bottom = true;
                }
                else {
                  // If the request yielded no data, then we have reached the bottom of the list
                  this.bottom = true;
                }
              } else if (page === 'quote') {
                if (res.data.length > 0) {
                  this.printedEntities = _.unionBy(this.printedEntities, res.data, 'id');
                  if (res.data.length < this.nbMaxResult) this.bottom = true;
                }
                else {
                  // If the request yielded no data, then we have reached the bottom of the list
                  this.bottom = true;
                }
              } else if (page === 'pendingBesoins') {
                if (res.data.length > 0) {
                  this.pendingBesoins = _.unionBy(this.pendingBesoins, res.data, 'id');
                  if (res.data.length < this.nbMaxResult) this.bottom = true;
                }
                else {
                  // If the request yielded no data, then we have reached the bottom of the list
                  this.bottom = true;
                }
              }
              this.loading2 = false;
            }).catch(e => {
              this.$handleError(e);
              if (page === 'expiredBesoins') {
                this.loading3 = false;
              } else {
              this.loading2 = false;
              }
            });
          }
        }
        if (!this.bottom2) {
          if (isVisible && !this.isScrolling2 && page === 'expiredBesoins') {
            this.loading3 = true;
            this.currentPage2++;
            this.isScrolling2 = true;
            return axios.get(url, {params}).then(res => {
              this.isScrolling2 = false;
              if (page === 'expiredBesoins') {
                if (res.data.length > 0) {
                  this.expiredBesoins = _.unionBy(this.expiredBesoins, res.data, 'id');
                  if (res.data.length < this.nbMaxResult) this.bottom2 = true;
                }
                else {
                  // If the request yielded no data, then we have reached the bottom of the list
                  this.bottom2 = true;
                }
              }
              else if (page === 'companyQuote') {
                if (res.data.length > 0) {
                  this.printedCompanyQuote = _.unionBy(this.printedCompanyQuote, res.data, 'id');
                  if (res.data.length < this.nbMaxResult) this.bottom2 = true;
                }
                else {
                  // If the request yielded no data, then we have reached the bottom of the list
                  this.bottom2 = true;
                }
              }
                this.loading3 = false;
            }).catch(e => {
              this.$handleError(e);
              if (page === 'expiredBesoins') {
                this.loading3 = false;
              } else {
                this.loading2 = false;
              }
            });
          }
        }
    },
    resetSearch() {
      this.firstAttempt = true;
      this.loading = true;
      this.loading2 = false;
      this.loading3 = false;
      this.printedEntities = [];
      this.pendingBesoins = [];
      this.expiredBesoins = [];
      this.bottom = false;
      this.bottom2 = false;
      this.currentPage = 2;
      this.currentPage2 = 2;

    },
  },
};
