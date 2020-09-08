<template>
  <div class="container">
    <a href="/admin/group">
      <button
        title="Annulez les modifications"
        type="button"
        class="w-40px p-0 rounded-circle btn-close btn"
      >
        <i class="fas fa-times "></i>
      </button>
    </a>
    <form>
      <div class="">
        <div class="register-card w-100 h-100">
          <!-- First row  -->
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <fieldset
                  id="name"
                  class="name"
                >
                  <label class="fontUbuntu fontSize16">{{ this.$t('admin.group.fields.name') }}</label>
                  <input
                    v-validate="'required'"
                    type="text"
                    name="name"
                    class="form-control"
                    :placeholder="$t('admin.group.placeholder.name')"
                    v-model="formFields.name"
                  >
                  <div
                    v-for="errorText in formErrors.name"
                    :key="'name_' + errorText"
                  >
                    <span class="fontUbuntu fontSize13 red-skb">{{ errorText }}</span>
                  </div>
                </fieldset>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <fieldset
                  id="code"
                  class="code"
                >
                  <label class="fontUbuntu fontSize16">{{ this.$t('admin.group.fields.code') }}</label>
                  <input
                    v-validate="'required'"
                    name="code"
                    type="text"
                    class="form-control"
                    :placeholder="$t('admin.group.placeholder.code')"
                    v-model="formFields.code"
                  >
                  <div
                    v-for="errorText in formErrors.code"
                    :key="'code_' + errorText"
                  >
                    <span class="fontUbuntu fontSize13 red-skb">{{ errorText }}</span>
                  </div>
                </fieldset>
              </div>
            </div>
          </div>
          <!-- Second row  -->
          <div class="row">
            <fieldset
              id="roles"
              class="col-12 roles"
            >
              <label class="fontUbuntu fontSize16">{{ $t('admin.group.fields.roles') }}</label>
              <dual-list
                v-if="rolesAtCreation !== null"
                :items="roles"
                :item-label-fields="['name']"
                :selected-items-at-creation="rolesAtCreation"
                @selected-items-change="$onSelectedItemsChange(arguments, 'roles')"
              />
              <input
                v-validate="'required'"
                v-model="formFields.roles"
                name="roles"
                class="d-none"
              >
              <div
                v-for="errorText in formErrors.roles"
                :key="'roles_' + errorText"
                class="line-height-1"
              >
                <span class="fontUbuntu fontSize13 red-skb">{{ errorText }}</span>
              </div>
            </fieldset>
          </div>
          <div class="row my-3">
            <fieldset
              id="utilisateurs"
              class="col-12 utilisateurs"
            >
              <label class="fontUbuntu fontSize16">{{ $t('admin.group.fields.utilisateurs') }}</label>
              <autocomplete
                ref="autocomplete"
                :min="3"
                :debounce="500"
                :on-should-render-child="$getUserLabel"
                :on-select="setUser"
                :on-before-ajax="() => loading = true"
                :on-ajax-loaded="onUsersAjaxLoaded"
                :placeholder="$t('admin.group.placeholder.utilisateurs')"
                param="autocomplete"
                url="/api/admin/utilisateurs"
                anchor="label"
                :classes="{input: 'form-control'}"
              />
              <input
                v-validate="'required'"
                v-model="formFields.utilisateurs"
                name="utilisateurs"
                class="d-none "
              >
              <div
                v-for="errorText in formErrors.utilisateurs"
                :key="'utilisateurs_' + errorText"
                class="line-height-1"
              >
                <span class="fontUbuntu fontSize13 red-skb">{{ errorText }}</span>
              </div>
            </fieldset>
          </div>
          <div class="row my-3">
            <div class="col-6 offset-3">
              <button
                type="button"
                class="btn button_skb fontUbuntu"
                @click="$validateForm()"
              >{{ this.groupId ? this.$t('commons.edit') :  this.$t('commons.create')}}</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
import axios from 'axios';
import Autocomplete from 'vue2-autocomplete-js';
import validatorRulesMixin from 'mixins/validatorRulesMixin';
import adminFormMixin from 'mixins/adminFormMixin';
import DualList from 'components/commons/dual-list';

export default {
  components: {
    DualList,
    Autocomplete
  },
  mixins: [
    validatorRulesMixin,
    adminFormMixin
  ],
  data () {
    return {
      API_URL: '/api/admin/groups' + (this.groupId ? `/${this.groupId}` : ''),
      rolesAtCreation: null,
      roles: [],
      formFields: {
        name: null,
        code: null,
        roles: [],
        utilisateurs: []
      },
      formErrors: {
        name: [],
        code: [],
        roles: [],
        utilisateurs: []
      }
    };
  },
  props: {
    groupId: {
      type: Number,
      default: null,
    }
  },
  created () {
    let promises = [];
    promises.push(axios.get('/api/admin/roles'));
    if (this.groupId) {
      promises.push(axios.get(this.API_URL));
    }
    return Promise.all(promises).then(res => {
      this.roles = res[0].data;
      if (this.groupId) {
        let group = res[1].data;
        this.$removeFieldsNotInForm(group, Object.keys(this.formFields));
        this.$setEditForm(group);
      }
      this.rolesAtCreation = _.cloneDeep(this.formFields.roles);
    }).catch(e => {
      console.log(e);
    });
  },
  methods: {
    setUser (utilisateur) {
      if (!this.find(utilisateur)) {
        this.$onAfterCreateEntity(arguments, 'utilisateurs', this.formFields);
      }
      this.$refs.autocomplete.setValue('');
    },

    /**
       * Called after each request. If the request returns an empty list, adds an error to the
       * autocomplete field.
       * Hide the loading image
       */
    onUsersAjaxLoaded (data) {
      if (data.length) {
        this.formErrors.utilisateurs = [];
      } else {
        this.formErrors.utilisateurs = [this.$t('utilisateur_does_not_exist')];
      }
      this.loading = false;
    },

    find (utilisateur) {
      return _.find(this.formFields.utilisateurs, ['id', utilisateur.id]);
    },
  },
}
</script>
