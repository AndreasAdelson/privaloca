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
          if ((isVisible && !this.isScrolling) || (isVisible && !this.isScrolling2)) {
            this.isScrolling = true;
            //Gestion double lazy laoding sur la meme page
            if (page === 'expiredBesoins') {
              this.loading3 = true;
              this.currentPage2++;
            } else {
              this.loading2 = true;
              this.currentPage++;
            }
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

              else if (page === 'expiredBesoins') {
                this.isScrolling2 = false;
                if (res.data.length > 0) {
                  this.expiredBesoins = _.unionBy(this.expiredBesoins, res.data, 'id');
                  if (res.data.length < this.nbMaxResult) this.bottom2 = true;
                }
                else {
                  // If the request yielded no data, then we have reached the bottom of the list
                  this.bottom2 = true;
                }
              }
              //Si ca vient de expiredBesoin c'est le loader3 qui se charge d'afficher le loader dans la liste.
              if (page === 'expiredBesoins') {
                this.loading3 = false;
              } else {
                this.loading2 = false;
              }

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
