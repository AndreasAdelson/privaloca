<template>
  <div class="row justify-content-between">
    <div class="col-10">
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

      <!-- <div class="row">
        <div class="col-12">
          <span class="underline">Réponses</span>
        </div>
      </div> -->
    </div>
    <div class="col-1 mr-1 button-row">
      <a
        class="btn btn-success w-100 mb-1"
        href="#"
      >
        <i class="fas fa-check" />
        Cloturer
      </a>
      <a
        class="btn button_skb_yellow mb-1"
        :href="'/services/edit/' + pendingBesoin.id"
      >
        <i class="fas fa-pencil-alt mr-1" />
        Modifier
      </a>
      <a
        href="#"
        class="btn button_skb mb-1"
        data-toggle="modal"
        :data-target="'#' + DELETE_CONFIRM_MODAL_ID"
        @click.prevent="cancelRequest()"
      >
        <i class="fas fa-ban mr-1" />
        Annuler
      </a>
    </div>
  </div>
</template>
<script>
  import moment from 'moment';
  import axios from 'axios';

  export default {
    props: {
      pendingBesoin: {
        type: Object,
        default: null
      }
    },
    data() {
      return {
        DELETE_CONFIRM_MODAL_ID: 'delete_confirmModal',

      };
    },
    computed: {
      dtCreated() {
        return moment(this.pendingBesoin.dt_created, 'DD/MM/YYYY H:mm:ss').format('[le] DD/MM/YYYY [à] H:mm');
      }
    },
    methods: {
      cancelRequest() {
        this.$emit('service-deleted', this.pendingBesoin.id);
      }
    },
  };
</script>
