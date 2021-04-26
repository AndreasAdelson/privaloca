<template>
  <div>
    <div class="col-12 card-body-opportunities">
      <div class="row justify-content-between mb-3 align-items-center">
        <div class="col-6 ">
          <div class="ml-2">
            <span class="bold">{{ $capitalise(opportunity.title) }}</span>
          </div>
        </div>
        <div class="col-1 text-right black-light-skb fontSize14">
          <span>{{ $getDateLabel(opportunity.dt_created) }}</span>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-11 mx-auto">
          <span>{{ opportunity.description }}</span>
        </div>
      </div>
      <div class="row justify-content-end">
        <div class="col-4 text-right black-light-skb fontSize14">
          <font-awesome-icon
            class="mt-2 mr-1"
            :icon="['fas', 'pencil-alt']"
          />
          <span>{{ nbAnswers }} {{ $tc('opportunity.customer.nb_answers', nbAnswers) }}</span><span v-if="isAnswered"> {{ $t('opportunity.customer.answered') }}</span>
        </div>
      </div>
      <div class="row no-gutters py-2 border-top">
        <div class="col-6 text-center mx-auto">
          <button
            v-if="!isAnswered"
            :disabled="loading"
            data-toggle="modal"
            :data-target="'#' + modalId"
            class="btn button_skb_white"
            @click="openModal()"
          >
            <span
              v-show="!isAnswered"
              :key="'fa-pencil' + index"
            ><font-awesome-icon
              class="mt-2 mr-1"
              :icon="['fas', 'pencil-alt']"
              size="lg"
            /></span>
            <span>{{ $t('opportunity.customer.propose_yourself') }}</span>
          </button>
          <div
            v-else
            class="yellow-login-skb bold"
          >
            <span
              v-show="isAnswered"
              :key="'fa-check' + index"
            >
              <font-awesome-icon
                class="mt-2 mr-1"
                :icon="['fas', 'check']"
                size="lg"
              />
            </span>
            <span>{{ $t('opportunity.customer.propose_yourself') }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import { EventBus } from 'plugins/eventBus';
  import _ from 'lodash';

  export default {
    components: {
    },
    props: {
      opportunity: {
        type: Object,
        default: () => new Object()
      },
      index: {
        type: Number,
        default: null
      },
      modalId: {
        type: String,
        default: ''
      }
    },
    data() {
      return {
        currentAnswer: new Object(),
        loading: false,

      };
    },
    computed: {
      isAnswered() {
        return this.opportunity.isAnswered;
      },

      cloneOpportunity() {
        return _.cloneDeep(this.opportunity);
      },

      nbAnswers() {
        // @ts-ignore
        return this.cloneOpportunity.answers.length;
      }
    },
    async created() {
      EventBus.$on('answer-modal-submit-called', async event => {
        this.loading = true;
      });
      EventBus.$on('answer-modal-submited', async event => {
        this.loading = false;
      });
    },
    methods: {
      openModal() {
        this.$emit('modal-openned');
      }
    },
  };
</script>
