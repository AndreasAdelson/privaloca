<template>
  <div class="container">
    <a href="/admin/role">
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
                  <label class="fontUbuntu fontSize14">{{ this.$t('admin.role.fields.name') }}</label>
                  <input
                    v-validate="'required'"
                    type="text"
                    name="name"
                    class="form-control"
                    :placeholder="$t('admin.role.placeholder.name')"
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
                  <label class="fontUbuntu fontSize14">{{ this.$t('admin.role.fields.code') }}</label>
                  <input
                    v-validate="'required'"
                    name="code"
                    type="text"
                    class="form-control"
                    :placeholder="$t('admin.role.placeholder.code')"
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
            <div class="col-12">
              <dual-list
                v-if="fonctionsAtCreation !== null"
                :items="fonctions"
                :item-label-fields="['description']"
                :selected-items-at-creation="fonctionsAtCreation"
                @selected-items-change="$onSelectedItemsChange(arguments, 'fonctions')"
              />
              <input
                v-validate="'required'"
                v-model="formFields.fonctions"
                name="fonctions"
                class="d-none"
              >
              <div
                v-for="errorText in formErrors.fonctions"
                :key="'fonctions_' + errorText"
                class="line-height-1"
              >
                <span class="fontSize10 redtxt">{{ errorText }}</span>
              </div>
            </div>
          </div>
          <div class="row my-3">
            <div class="col-6 offset-3">
              <button
                type="button"
                class="btn button_skb fontUbuntu"
                @click="$validateForm()"
              >{{ this.roleId ? this.$t('commons.edit') :  this.$t('commons.create')}}</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
import axios from 'axios';
import validatorRulesMixin from 'mixins/validatorRulesMixin';
import adminFormMixin from 'mixins/adminFormMixin';
import DualList from 'components/commons/dual-list';

export default {
  components: {
    DualList
  },
  mixins: [
    validatorRulesMixin,
    adminFormMixin
  ],
  data () {
    return {
      API_URL: '/api/admin/roles' + (this.roleId ? `/${this.roleId}` : ''),
      fonctionsAtCreation: null,
      fonctions: [],
      formFields: {
        name: null,
        code: null,
        fonctions: [],
      },
      formErrors: {
        name: [],
        code: [],
        fonctions: [],
      }
    };
  },
  props: {
    roleId: {
      type: Number,
      default: null,
    }
  },
  created () {
    let promises = [];
    promises.push(axios.get('/api/admin/fonctions'));
    if (this.roleId) {
      promises.push(axios.get(this.API_URL));
    }
    return Promise.all(promises).then(res => {
      this.fonctions = res[0].data;
      console.log(res, 'res');
      if (this.roleId) {
        let role = res[1].data;
        this.$removeFieldsNotInForm(role, Object.keys(this.formFields));
        this.$setEditForm(role);
      }
      this.fonctionsAtCreation = _.cloneDeep(this.formFields.fonctions);
    }).catch(e => {
      console.log(e);
    });
  },
  methods: {

  },
}
</script>
