<template>
  <div
    :id="id"
    :aria-labelledby="id + 'Label'"
    class="modal fade"
    data-backdrop="static"
    data-keyboard="false"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
  >
    <div
      class="modal-dialog modal-dialog-centered"
      role="document"
    >
      <div class="modal-content pb-1">
        <div class="modal-header border-bottom-0">
          <h5
            :id="id + 'Label'"
            class="modal-title"
          >
            {{ titleText }}
          </h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
            @click="$emit('confirm-modal-no')"
          >
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <nl2br
            v-if="nl2br"
            tag="p"
            :text="bodyText"
          />
          <span v-else>{{ bodyText }}</span>
        </div>

        <div
          v-if="areButtonsOnSameLine"
          key="areButtonsOnSameLine"
          class="modal-footer border-top-0"
        >
          <div>
            <button
              type="button"
              class="btn btn-secondary redbg"
              data-dismiss="modal"
              @click="$emit('confirm-modal-no')"
            >
              <span class="whitetxt">{{ buttonNoText }}</span>
            </button>
          </div>
          <div>
            <button
              type="button"
              class="btn button_skb"
              data-dismiss="modal"
              @click="$emit('confirm-modal-yes', notificationContent)"
            >
              <span class="whitetxt">{{ buttonYesText }}</span>
            </button>
          </div>
        </div>
        <div
          v-else
          key="areButtonsOnSameLine"
        >
          <div class="modal-footer border-top-0">
            <div>
              <button
                type="button"
                class="btn btn-secondary redbg"
                data-dismiss="modal"
                @click="$emit('confirm-modal-no')"
              >
                <span class="whitetxt">{{ buttonNoText }}</span>
              </button>
            </div>
          </div>
          <div class="modal-footer border-top-0 pt-1">
            <div>
              <button
                type="button"
                class="btn button_skb"
                data-dismiss="modal"
                @click="$emit('confirm-modal-yes', notificationContent)"
              >
                <span class="whitetxt">{{ buttonYesText }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      id: {
        type: String,
        default: ''
      },

      titleText: {
        type: String,
        default: ''
      },
      bodyText: {
        type: String,
        default: ''
      },
      buttonYesText: {
        type: String,
        default: ''
      },
      buttonNoText: {
        type: String,
        default: ''
      },
      areButtonsOnSameLine: {
        type: Boolean,
        default: false
      },
      hasComment: {
        type: Boolean,
        default: false
      },
      notificationContentMaxLength: {
        type: Number,
        default: 255
      },
      commentLabel: {
        type: String,
        default: ''
      },
      nl2br: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {
        notificationContent: ''
      };
    },
  };
</script>
