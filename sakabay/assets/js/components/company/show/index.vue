<template>
  <div class="skb-body container">
    <div v-if="loading">
      <div class="loader-container-full">
        <div class="loader" />
      </div>
    </div>
    <div v-else-if="company">
      <div class="row mt-4 card-skb">
        <div class="col-12">
          <b-img
            v-if="company.image_profil"
            :src="'/build/images/uploads/' + company.url_name + '/' + company.image_profil"
            class="company-image-2"
          />
          <b-img
            v-else
            src="/build/default_company.jpg"
            class="company-image-2"
          />
          <div class="header-main-info">
            <div class="company-name">
              <h1 class="fontSize32 fontPoppins m-0">
                {{ company.name }}
              </h1>
            </div>
            <div class="category mb-1">
              <span class="fontSize18 fontAlice">{{ company.category.name }}</span>
            </div>
            <div
              v-if="company.sous_categorys.length !=0 && isSubscriptionActive"
              class="row activity-domain"
            >
              <span
                v-for="(sousCategory, index2) in company.sous_categorys"
                :key="'sousCategory_' + index2"
                class="multiselect__tag_no_icon"
              >{{ sousCategory.name }}</span>
            </div>
          </div>
        </div>
        <div class="col-2" />
      </div>

      <div class="row navigation-menu card-skb">
        <div class="col-12">
          <a
            href="#presentation"
            class="fontPoppins"
            :class="presentationActive ? 'navigation-link-active': 'navigation-link'"
            @click="activePresentation()"
          >{{ $t("company.nav_title.presentation") }}</a>
          <a
            href="#jobOffers"
            class="fontPoppins"
            :class="jobOfferActive ? 'navigation-link-active': 'navigation-link'"
            @click="activeJobOffer()"
          >{{ $t("company.nav_title.job_offers") }}</a>
          <a
            href="#comments"
            class="fontPoppins"
            :class="commentActive ? 'navigation-link-active': 'navigation-link'"
            @click="activeComment()"
          >{{ $t("company.nav_title.comments") }}</a>
        </div>
      </div>
      <div v-if="presentationActive">
        <presentation-page
          :is-subscription-active="isSubscriptionActive"
          :company="company"
        />
      </div>

      <div v-else-if="jobOfferActive">
        <p>Work in progress ... Listing des offres d'emplois</p>
      </div>

      <div v-else-if="commentActive">
        <comment-page :company="company" />
      </div>
    </div>
  </div>
</template>
<script>
  import axios from 'axios';
  import presentationPage from './presentation';
  import commentPage from './comment';
  import _ from 'lodash';
  export default {
    components: {
      presentationPage,
      commentPage
    },
    props: {
      companyUrlName: {
        type: String,
        default: ''
      },
      isSubscriptionActive: {
        type: Boolean,
        default: false
      }
    },

    data() {
      return {
        loading: true,
        company: null,
        presentationActive: true,
        jobOfferActive: false,
        commentActive: false,
      };
    },
    async created() {
      return axios.get('/api/entreprise/' + this.companyUrlName, {params: {'serial_group': 'api_companies'}})
        .then(response => {
          this.company = _.cloneDeep(response.data);
          this.loading = false;
        }).catch(e => {
          this.loading = false;
          this.$handleError(e);
        });
    },
    methods: {
      activePresentation() {
        this.presentationActive = true;
        this.jobOfferActive = false;
        this.commentActive = false;
      },
      activeJobOffer() {
        this.presentationActive = false;
        this.jobOfferActive = true;
        this.commentActive = false;
      },
      activeComment() {
        this.presentationActive = false;
        this.jobOfferActive = false;
        this.commentActive = true;
      },
    },
  };
</script>
