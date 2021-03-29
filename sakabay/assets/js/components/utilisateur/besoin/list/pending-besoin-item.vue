<template>
  <div class="row">
    <div class="col">
      <div class="row">
        <div class="col-11 border-right">
          <div class="row mb-2">
            <div class="col-12 ">
              <span class="bold">{{ pendingBesoin.title }}</span>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <span class="underline">Catégorie</span>
            </div>
            <div class="col-4">
              <span class="underline">Activités</span>
            </div>
            <div class="col-4">
              <span class="underline">Date de publication</span>
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
              <span class="underline">Description</span>
              <nl2br
                tag="p"
                :text="pendingBesoin.description"
              />
            </div>
          </div>
        </div>
        <div class="col-1 my-auto">
          <a
            class="btn btn-success w-100 mb-1"
            href="#"
          >
            <font-awesome-icon :icon="['fas', 'check']" />
            Cloturer
          </a>
          <a
            class="btn button_skb_yellow mb-1"
            :href="'/services/edit/' + pendingBesoin.id"
          >
            <font-awesome-icon
              class="mr-1"
              :icon="['fas', 'pencil-alt']"
            />
            Modifier
          </a>
          <a
            href="#"
            class="btn button_skb mb-1"
            data-toggle="modal"
            :data-target="'#' + DELETE_CONFIRM_MODAL_ID"
            @click.prevent="cancelRequest()"
          >
            <font-awesome-icon :icon="['fas', 'ban']" />
            Annuler
          </a>
        </div>
      </div>
      <div
        v-if="nbAnswer > 0"
        class="row list-foldable foldable-closed"
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
              <span class="underline"> {{ nbAnswer }} Réponses</span>
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
            class="row mt-3 answer-card"
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
                <a
                  target="_blank"
                  class="col my-auto"
                  :href="'/entreprise/' + answer.company.url_name"
                >
                  <h6 class="bold underline">{{ answer.company.name }}</h6>
                </a>
              </div>
              <div class="row">
                <div class="col-10">
                  <nl2br
                    tag="p"
                    :text="answer.message"
                  />
                </div>
                <div class="col-2 align-self-end pb-1">
                  <btn
                    class="btn btn-success w-100 mb-1"
                  >
                    <font-awesome-icon :icon="['fas', 'check']" />
                    Demander un devis
                  </btn>
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

  export default {
    mixins: [
    ],
    props: {
      pendingBesoin: {
        type: Object,
        default: null
      }
    },
    data() {
      return {
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
        this.$emit('service-deleted', this.pendingBesoin.id);
      },
      toggleIcon() {
        if (this.iconArrow === 'chevron-right') {
          this.iconArrow = 'chevron-down';
        } else {
          this.iconArrow = 'chevron-right';
        }
      }
    },
  };
</script>
