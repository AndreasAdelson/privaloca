<template>
  <div class="ml-2 mt-2 card h-100 shadow-md whitebg overflow-hidden text-center minWidth-250px">

    <div class="card-body px-4 py-3 row">
      <div class="col-9 fontSize15 line-height-1 font-weight-bold d-flex">
        <span
          v-if="user"
          class="d-flex align-items-center break-all"
        >{{ user.last_name ? user.last_name : '' }} {{ user.first_name ? user.first_name : '' }}</span>
      </div>
      <div class="col-3 ">
        <button
          type="button"
          class="btn btn-delete rounded-circle"
          @click="deleteEntity()"
        >
          <i class="fas fa-times "></i>
        </button>
      </div>
    </div>

  </div>
</template>

<script>

export default {
  components: {
  },

  props: {
    user: {
      type: Object,
      default: null
    },
  },

  data () {
    return {
    };
  },

  methods: {
    /**
     * Triggered when the event "confirm-modal-yes" is launched from the confirmModal component.
     * Hide the modal of confirmModal and send a "delete" event to the current component's parent.
     */
    deleteEntity () {
      this.$emit('delete-entity');
    },
  },
};
</script>
