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
            :href="'/admin/role/edit/' + roleId"
          >
            <b-button class="button_skb">{{ this.$t('commons.edit') }}</b-button>
          </a>
        </div>
      </div>
      <a href="/admin/role">
        <button
          :title="$t('commons.go_back')"
          type="button"
          class="w-40px mt-4 p-0 rounded-circle btn-close btn"
        >
          <i class="fas fa-times "></i>
        </button>
      </a>
      <div class="register-card mt-3 w-100 h-100">

        <div class="row">
          <div class="col-6">
            <span class="fontPatua fontSize20">{{ $t('admin.role.fields.name') }}</span>
          </div>
          <div class="col-6">
            <span class="fontPatua fontSize20">{{ $t('admin.role.fields.code') }}</span>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-6">
            <span class="fontHelveticaOblique fontSize18">{{ this.role.name.toUpperCase() }}</span>
          </div>
          <div class="col-6">
            <span class="fontHelveticaOblique fontSize18">{{ this.role.code }}</span>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <span class="fontPatua fontSize20">{{ $t('admin.role.fields.fonctions') }}</span>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-6">
            <ul>
              <li
                v-for="(fonction, index) in role.fonctions"
                :key="'fonction_'+ index"
                class="fontHelveticaOblique fontSize18"
              >
                {{ fonction.description }}
              </li>
            </ul>
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
    roleId: {
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
      role: null,
      loading: false
    }
  },
  async created () {
    if (this.roleId) {
      this.loading = true;
      return axios.get('/api/admin/roles/' + this.roleId)
        .then(response => {
          this.role = response.data;
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
