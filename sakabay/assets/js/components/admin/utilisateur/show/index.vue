<template>
  <div class="skb-body container ">
    <div v-if="loading">
    </div>
    <div v-else>
      <div
        v-if="canEdit"
        class="row mt-3 "
      >
        <div class="col-2 offset-10 justify-content-end">
          <a
            class="float-right"
            :href="'/admin/utilisateur/edit/' + utilisateurId"
          >
            <b-button class="button_skb">{{ this.$t('commons.edit') }}</b-button>
          </a>
        </div>
      </div>
      <a href="/admin/utilisateur">
        <button
          :title="$t('go_back')"
          type="button"
          class="w-40px mt-4 p-0 rounded-circle btn-close btn"
        >
          <i class="fas fa-times "></i>
        </button>
      </a>
      <div class="register-card mt-3 w-100 h-100">

        <div class="row my-2">
          <div class="col-6">
            <b-img
              v-if="this.utilisateur && this.utilisateur.image_profil != null"
              class="rounded-circle logo-size-75"
              :src="urlImageProfil"
            />
            <avatar
              v-else
              class="p-0"
              :username="this.utilisateur && this.utilisateur.first_name + ' ' + this.utilisateur.last_name"
              :size="75"
            ></avatar>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <span class="fontPatua fontSize20">{{ $t('admin.user.fields.last_name') }}</span>
          </div>
          <div class="col-6">
            <span class="fontPatua fontSize20">{{ $t('admin.user.fields.first_name') }}</span>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-6">
            <span class="fontHelveticaOblique fontSize18">{{ this.utilisateur.last_name.toUpperCase() }}</span>
          </div>
          <div class="col-6">
            <span class="fontHelveticaOblique fontSize18">{{ this.utilisateur.first_name }}</span>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <span class="fontPatua fontSize20">{{ $t('admin.user.fields.email') }}</span>
          </div>
          <div class="col-6">
            <span class="fontPatua fontSize20">{{ $t('admin.user.fields.login') }}</span>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-6">
            <span class="fontHelveticaOblique fontSize18">{{ this.utilisateur.email }}</span>
          </div>
          <div class="col-6">
            <span class="fontHelveticaOblique fontSize18">{{ this.utilisateur.login.toUpperCase() }}</span>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>
<script>
import axios from 'axios';
import Avatar from 'vue-avatar';

export default {
  components: {
    Avatar
  },
  props: {
    utilisateurId: {
      type: Number,
      default: null
    },
    canEdit: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      utilisateur: null,
      loading: false
    }
  },
  async created () {
    if (this.utilisateurId) {
      this.loading = true;
      return axios.get('/api/admin/utilisateurs/' + this.utilisateurId)
        .then(response => {
          this.utilisateur = response.data;
          this.loading = false;
        }).catch(error => {
          console.log(error);
        });
    }
  },
  methods: {

  },
}
</script>>
