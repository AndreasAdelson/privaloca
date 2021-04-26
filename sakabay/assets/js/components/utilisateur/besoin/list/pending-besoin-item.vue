<template>
  <div class="row">
    <div class="col-12">
      <div class="row">
        <div class="col-11 border-right">
          <div class="row mb-2">
            <div class="col-12 ">
              <span class="bold">{{ pendingBesoin.title }}</span>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <span class="underline">{{ $t('besoin.label.category') }}</span>
            </div>
            <div class="col-4">
              <span class="underline">{{ $t('besoin.label.activity') }}</span>
            </div>
            <div class="col-4">
              <span class="underline">{{ $t('besoin.label.dt_created') }}</span>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <span class="fontAlice">{{ pendingBesoin.category.name }}</span>
            </div>
            <div class="col-4">
              <span
                v-for="(sousCategory, index) in pendingBesoin.sous_categorys"
                :key="'sousCategory_' + index"
                class="multiselect__tag_no_icon fontAlice"
              >{{ sousCategory.name }}</span>
            </div>
            <div class="col-4">
              <span class="fontAlice">{{ dtCreated }}</span>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <span class="underline">{{ $t('besoin.label.description') }}</span>
              <nl2br
                tag="p"
                :text="pendingBesoin.description"
              />
            </div>
          </div>
        </div>
        <div class="col-1 my-auto">
          <button
            class="btn btn-success w-100 mb-1"
          >
            <font-awesome-icon :icon="['fas', 'check']" />
            {{ $t('besoin.close') }}
          </button>
          <button
            class="btn button_skb_yellow mb-1"
            :href="'/services/edit/' + pendingBesoin.id"
          >
            <font-awesome-icon
              class="mr-1"
              :icon="['fas', 'pencil-alt']"
            />
            {{ $t('commons.edit') }}
          </button>
          <button
            class="btn button_skb mb-1"
            data-toggle="modal"
            :data-target="'#' + DELETE_CONFIRM_MODAL_ID"
            @click.prevent="cancelRequest()"
          >
            <font-awesome-icon :icon="['fas', 'ban']" />
            {{ $t('commons.cancel') }}
          </button>
        </div>
      </div>
      <div
        v-if="nbAnswer > 0"
        class="row mt-2 list-foldable foldable-closed"
      >
        <div class="col-12 border-top">
          <div
            class="row py-3 clickable"
            :class="iconArrow != 'chevron-right' ? 'border-bottom' : ''"
            @click="toggleIcon()"
          >
            <div
              class="col-6"
            >
              <span class="fontPoppins fontSize12 py-1 px-2 orange-gradiant white-skb rounded">{{ nbAnswer }}</span> <span class="underline">{{ $tc('opportunity.customer.nb_answers', nbAnswer) }} </span>
            </div>
            <div
              class="col-6 ml-auto"
            >
              <div class="icon text-right">
                <font-awesome-icon
                  :icon="['fas', iconArrow]"
                />
              </div>
            </div>
          </div>
        </div>
        <div
          v-show="iconArrow != 'chevron-right'"
          class="foldable-content col-12"
        >
          <div
            v-for="(answer, index) in pendingBesoin.answers"
            :key="'answer_' + index"
            :class="setStatusAnswer(answer)"
            class="row mt-2 answer-card"
          >
            <div class="col-12">
              <div class="row my-2">
                <b-img
                  v-if="answer.company.image_profil"
                  :src="'/build/images/uploads/' + answer.company.url_name + '/' + answer.company.image_profil"
                  class="company-image-3"
                />
                <b-img
                  v-else
                  src="/build/default_company.jpg"
                  class="company-image-3"
                />

                <h6 class="col my-auto">
                  <a
                    target="_blank"
                    class="bold underline"
                    :href="'/entreprise/' + answer.company.url_name"
                  >
                    {{ answer.company.name }}
                  </a>
                </h6>
              </div>
              <div class="row">
                <div class="col-10">
                  <nl2br
                    tag="p"
                    :text="answer.message"
                  />
                </div>
                <div class="col-2 align-self-end pb-1">
                  <button
                    v-if="!answer.request_quote && !answer.quote"
                    :disabled="loading"
                    data-toggle="modal"
                    :data-target="'#' + REQUEST_QUOTE_MODAL_ID"
                    class="btn button_skb_white w-100 mb-1"
                    @click.prevent="requestQuote(answer.company.name, answer.id, index)"
                  >
                    <font-awesome-icon
                      class="mr-2"
                      :icon="['fas', 'pencil-alt']"
                    />
                    {{ $t('besoin.request_quote') }}
                  </button>
                  <div
                    v-else-if="answer.request_quote && !answer.quote"
                    aria-disabled
                    class="btn btn_skb_green_disabled w-100 mb-1"
                  >
                    <font-awesome-icon
                      class="mr-2"
                      :icon="['fas', 'check']"
                    />

                    {{ $t('besoin.quote_requested') }}
                  </div>
                  <div
                    v-else-if="answer.request_quote && answer.quote"
                    aria-disabled
                    class="btn btn_skb_green_disabled w-100 mb-1"
                  >
                    <font-awesome-icon
                      class="mr-2"
                      :icon="['fas', 'paper-plane']"
                    />

                    {{ $t('besoin.quote_sent') }}
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
  import moment from 'moment';
  import { EventBus } from 'plugins/eventBus';

  export default {
    mixins: [
    ],
    props: {
      loading: {
        type: Boolean,
        default: false
      },
      pendingBesoin: {
        type: Object,
        default: null
      }
    },
    data() {
      return {
        REQUEST_QUOTE_MODAL_ID: 'request_quoteModal',
        DELETE_CONFIRM_MODAL_ID: 'delete_confirmModal',
        iconArrow: 'chevron-right'
      };
    },
    computed: {
      dtCreated() {
        return moment(this.pendingBesoin.dt_created, 'DD/MM/YYYY H:mm:ss').format('[le] DD/MM/YYYY [à] H:mm');
      },
      nbAnswer() {
        return this.pendingBesoin.answers.length;
      }
    },
    methods: {
      cancelRequest() {
        this.$emit('delete-modal-opened');
      },
      requestQuote(companyName, answerId, answerIndex) {
        this.$emit('request-quote-modal-opened', {company_name: companyName, answer_id: answerId, answer_index: answerIndex});
      },
      toggleIcon() {
        if (this.iconArrow === 'chevron-right') {
          this.iconArrow = 'chevron-down';
        } else {
          this.iconArrow = 'chevron-right';
        }
      },
      setStatusAnswer(answer) {
        let cssClass;
        if (!answer.request_quote && !answer.quote) {
          cssClass = 'yellow-light-bg-skb';
        }
        else if(answer.request_quote && !answer.quote) {
          cssClass = 'blue-light-bg-skb';
        }
        else if (answer.request_quote && answer.quote) {
          cssClass = 'green-light-bg-skb';
        }
        return cssClass;
      }
    },
  };
</script>
