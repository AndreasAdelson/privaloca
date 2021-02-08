<template>
  <div class="row justify-content-between">
    <div class="col-10">
      <div class="row mb-2">
        <div class="col-12 ">
          <span class="bold">{{ expiredBesoin.title }}</span>
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
          <span class="fontAlice">{{ expiredBesoin.category.name }}</span>
        </div>
        <div class="col-4">
          <span
            v-for="(sousCategory, index) in expiredBesoin.sous_categorys"
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
            :text="expiredBesoin.description"
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
      <button class="btn btn-success w-100 mb-1">
        <i class="fas fa-sync-alt" />
        Relancer
      </button>
      <button class="btn button_skb mb-1">
        <i class="fas fa-ban" />
        Supprimer
      </button>
    </div>
  </div>
</template>
<script>
  import moment from 'moment';
  import axios from 'axios';

  export default {
    props: {
      expiredBesoin: {
        type: Object,
        default: null
      }
    },
    computed: {
      dtCreated() {
        return moment(this.expiredBesoin.dt_created, 'DD/MM/YYYY H:mm:ss').format('[le] DD/MM/YYYY [à] H:mm');
      },
    },
    methods: {
      cancelRequest() {
        axios.delete('/api/besoins/' + this.expiredBesoin.id)
          .then(res => {
            this.$emit('service-deleted');
          })
          .catch(e => {
            this.$handleError(e);
          });
      }
    },
  };
</script>
