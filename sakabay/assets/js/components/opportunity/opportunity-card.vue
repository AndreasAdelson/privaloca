<template>
  <div>
    <div class="col-12 card-body-opportunities">
      <div class="row justify-content-between mb-3 align-items-center">
        <div class="col-6 ">
          <div class="ml-2">
            <span class="bold">{{ $capitalise(this.opportunity.title) }}</span>
          </div>
        </div>
        <div class="col-1 text-right black-light-skb">
          <span>{{ $getDateLabel(this.opportunity.dt_created) }}</span>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-11 mx-auto">
          <span>{{ this.opportunity.description }}</span>
        </div>
      </div>
      <div class="row no-gutters py-2 border-top">
        <div class="col-6 text-center mx-auto">
          <button
            v-if="!isAnswered"
            data-toggle="modal"
            data-target="#answerOpportunityModal"
            class="btn button_skb_transparent"
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
            <span>Se proposer</span>
          </button>
          <button
            v-else
            data-toggle="modal"
            data-target="#answerOpportunityModal"
            class="btn button_skb_transparent_answered"
            @click="openModal()"
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
            <span>Se proposer</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import EventBus from 'plugins/eventBus';
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
      }
    },
    data() {
      return {
        currentAnswer: new Object()
      };
    },
    computed: {
      isAnswered() {
        return this.opportunity.isAnswered;
      }
    },
    methods: {
      openModal() {
        this.$emit('modal-openned');
      }
    },
  };
</script>
