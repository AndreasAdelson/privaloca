import _ from 'lodash';
import axios from 'axios';

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
        if(!this.bottom) {
          if (isVisible && !this.isScrolling) {
            this.isScrolling = true;
            this.loading2 = true;
            this.currentPage++;
            return axios.get(url, {params}).then(res => {
              this.loading2 = false;
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
      this.printedEntities = [];
      this.bottom = false;
      this.currentPage = 2;
    },
  },
};
