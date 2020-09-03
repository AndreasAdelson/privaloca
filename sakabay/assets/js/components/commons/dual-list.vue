<template>
  <div class="row">
    <div class="dual-list list-left col-md-5">
      <div class="well text-right">
        <div class="row">
          <div class="col-10">
            <input
              class="w-100 form-control"
              v-model="unselectedItemsSearchText"
              :placeholder="$t('commons.rechercher')"
              type="search"
              name="SearchDualList"
            >
          </div>
          <div class="col-2">
            <div class="btn-group w-100 h-100">
              <a
                class="btn selector border pt-2"
                :title="$t('commons.select_all')"
                @click="toggleAllUnselectedItems()"
              >
                <i class="far fa-arrow-alt-circle-down"></i>
              </a>
            </div>
          </div>
        </div>

        <ul class="list-group mt-3">
          <li
            v-for="(unselectedItem, index) in printedUnselectedItems"
            :class="arrayContainsObject(clickedItems, unselectedItem) ? 'active' : ''"
            :key="'unselectedItem_No. ' + index"
            class="list-group-item cursorPointer"
            @click="toggleInClickedItems(unselectedItem)"
          >{{ getItemLabel(unselectedItem) }}</li>
        </ul>

      </div>
    </div>

    <div class="list-arrows col-md-2 text-center mt-5 pt-5">
      <button
        type="button"
        :title="$t('commons.add')"
        class="btn button_skb move-right mb-4"
        @click="selectItems()"
      >
        <i class="far fa-arrow-alt-circle-right"></i>
      </button>
      <br>
      <button
        type="button"
        :title="$t('commons.remove')"
        class="btn button_skb move-left mb-4"
        @click="unselectItems()"
      >
        <i class="far fa-arrow-alt-circle-left"></i>
      </button>
    </div>

    <div class="dual-list list-right col-md-5">
      <div class="well">
        <div class="row">

          <div class="col-2">
            <div class="btn-group w-100 h-100">
              <a
                class="btn selector border pt-2"
                :title="$t('commons.select_all')"
                @click="toggleAllSelectedItems()"
              >
                <i class="far fa-arrow-alt-circle-down"></i>
              </a>
            </div>
          </div>
          <div class="col-10">
            <input
              class="w-100 form-control"
              v-model="selectedItemsSearchText"
              :placeholder="$t('commons.rechercher')"
              type="search"
              name="SearchDualList"
            >
          </div>
        </div>

        <ul class="list-group mt-3">
          <li
            v-for="(selectedItem, index) in printedSelectedItems"
            :class="arrayContainsObject(clickedItems, selectedItem) ? 'active' : ''"
            :key="'selectedItem_No. ' + index"
            class="list-group-item cursorPointer"
            @click="toggleInClickedItems(selectedItem)"
          >{{ getItemLabel(selectedItem) }}</li>
        </ul>

      </div>
    </div>
  </div>
</template>

<script>
import _ from 'lodash';

export default {
  props: {
    items: {
      type: Array,
      default: () => new Array()
    },

    itemLabelFields: {
      type: Array,
      default: () => new Array()
    },

    doResetList: {
      type: Boolean,
      default: false
    },

    selectedItemsAtCreation: {
      type: Array,
      default: () => new Array()
    },
  },

  data () {
    return {
      clickedItems: [],
      selectedItems: [],
      unselectedItems: [],
      unselectedItemsSearchText: '',
      selectedItemsSearchText: '',
    };
  },

  computed: {
    printedSelectedItems () {
      return _.filter(this.selectedItems, item => {
        return this.getItemLabel(item).toLowerCase().match(new RegExp(this.selectedItemsSearchText.trim().toLowerCase()), 'g');
      });
    },

    printedUnselectedItems () {
      return _.filter(this.unselectedItems, item => {
        return this.getItemLabel(item).toLowerCase().match(new RegExp(this.unselectedItemsSearchText.trim().toLowerCase()), 'g');
      });
    },

    areAllSelectedItemsClicked () {
      let allClicked = true;

      for (let i = 0; i < this.selectedItems.length && allClicked; i++) {
        if (!this.arrayContainsObject(this.clickedItems, this.selectedItems[i])) {
          allClicked = false;
        }
      }

      return allClicked && this.selectedItems.length > 0;
    },

    areAllUnselectedItemsClicked () {
      let allClicked = true;

      for (let i = 0; i < this.unselectedItems.length && allClicked; i++) {
        if (!this.arrayContainsObject(this.clickedItems, this.unselectedItems[i])) {
          allClicked = false;
        }
      }

      return allClicked && this.unselectedItems.length > 0;
    },
  },

  watch: {
    selectedItems () {
      this.$emit('selected-items-change', this.selectedItems);
    },

    /**
     * Put all items in unselectedItems when the parent component set its
     * selected items to null or undefined.
     */
    doResetList (newValue) {
      if (newValue) {
        this.resetList();
      }
    }
  },

  created () {
    this.selectedItems = _.cloneDeep(this.selectedItemsAtCreation);
    this.unselectedItems = _.cloneDeep(this.items);
  },

  mounted () {
    this.unselectedItems = _.filter(this.items, item => {
      return !_.map(this.selectedItems, 'id').includes(item.id);
    });
    this.orderItems();
  },

  methods: {
    resetList () {
      this.clickedItems = [];
      this.selectedItems = [];
      this.unselectedItemsSearchText = '';
      this.selectedItemsSearchText = '';
      this.unselectedItems = _.cloneDeep(this.items);
      this.orderItems();
    },

    /**
     * Order selectedItems and unselectedItems by the first field of itemLabelFields
     */
    orderItems () {
      this.unselectedItems = this.unselectedItems.sort((a, b) => {
        return a[this.itemLabelFields[0]] - b[this.itemLabelFields[0]];
      });
      this.selectedItems = this.selectedItems.sort((a, b) => {
        return a[this.itemLabelFields[0]] - b[this.itemLabelFields[0]];
      });
    },

    /**
     * Add all items from clickedItems to selectedItems, and remove them from unselectedItems
     */
    selectItems () {
      this.clickedItems.forEach(clickedItem => {
        if (this.arrayContainsObject(this.printedUnselectedItems, clickedItem)) {
          let indexOfRemovedItem = this.removeItemFromArray(clickedItem, this.unselectedItems);
          if (indexOfRemovedItem > -1) {
            this.selectedItems.push(clickedItem);
          }
        }
      });
      this.orderItems();
    },

    /**
     * Add all items from clickedItems to unselectedItems, and remove them from selectedItems
     */
    unselectItems () {
      this.clickedItems.forEach(clickedItem => {
        if (this.arrayContainsObject(this.printedSelectedItems, clickedItem)) {
          let indexOfRemovedItem = this.removeItemFromArray(clickedItem, this.selectedItems);
          if (indexOfRemovedItem > -1) {
            this.unselectedItems.push(clickedItem);
          }
        }
      });
      this.orderItems();
    },

    /**
     * Put all selectedItems in clickedItems.
     * If all selected items are already in clickedItems, remove all
     * selected items from clickedItems.
     */
    toggleAllSelectedItems () {
      if (this.areAllSelectedItemsClicked) {
        this.selectedItems.forEach(selectedItem => {
          this.removeItemFromArray(selectedItem, this.clickedItems);
        });
      } else {
        this.selectedItems.forEach(selectedItem => {
          if (!this.arrayContainsObject(this.clickedItems, selectedItem)) {
            this.clickedItems.push(selectedItem);
          }
        });
      }
    },

    /**
     * Put all unselectedItems in clickedItems.
     * If all unselected items are already in clickedItems, remove all
     * unselected items from clickedItems.
     */
    toggleAllUnselectedItems () {
      if (this.areAllUnselectedItemsClicked) {
        this.unselectedItems.forEach(unselectedItem => {
          this.removeItemFromArray(unselectedItem, this.clickedItems);
        });
      } else {
        this.unselectedItems.forEach(unselectedItem => {
          if (!this.arrayContainsObject(this.clickedItems, unselectedItem)) {
            this.clickedItems.push(unselectedItem);
          }
        });
      }
    },

    /**
     * Add/Remove the given item in clickedItemsSet
     * @param {string} item  A stringified object
     */
    toggleInClickedItems (item) {
      if (this.arrayContainsObject(this.clickedItems, item)) {
        this.removeItemFromArray(item, this.clickedItems);
      } else {
        this.clickedItems.push(item);
      }
    },

    /**
     * Remove the given item from the given array.
     *
     * @param {object} itemToRemove
     * @param {array} array
     * @returns {integer} The index of the item removed. -1 if the given item was not in the given array.
     */
    removeItemFromArray (itemToRemove, array) {
      let indexOfItemToDelete = _.findIndex(array, item => {
        return this.areSameItems(item, itemToRemove);
      });
      if (indexOfItemToDelete > -1) {
        array.splice(indexOfItemToDelete, 1);
      }
      return indexOfItemToDelete;
    },

    /**
     * Check if the given object is in the given array.
     * @param {array} array
     * @param {object} object
     */
    arrayContainsObject (array, object) {
      let contains = false;
      for (let i = 0; i < array.length && !contains; i++) {
        contains = this.areSameItems(array[i], object);
      }
      return contains;
    },

    /**
     * Compare two items.
     * @param {object} item1
     * @param {object} item2
     * @param {boolean}
     */
    areSameItems (item1, item2) {
      return JSON.stringify(item1) === JSON.stringify(item2);
    },

    /**
     * Return the label of an item. The item's label can contain from one to three fields of the item (depending on the itemLabelFields variable length).
     * If the label contains one field, it returns only this field on the item : « field »
     * If the label contains two fields, it returns : « [field1] field2 »
     * If the label contains three fields, it returns : « [field1] field2 (field3) »
     * @param {string} item
     * @returns {string} the object's label
     */
    getItemLabel (item) {
      switch (this.itemLabelFields.length) {
        case 1:
          return item[this.itemLabelFields[0]];
        case 2:
          return '[' + item[this.itemLabelFields[0]] + '] ' + item[this.itemLabelFields[1]];
        case 3:
          return '[' + item[this.itemLabelFields[0]] + '] ' + item[this.itemLabelFields[1]] + ' (' + item[this.itemLabelFields[2]] + ')';
      }
    },
  },
};
</script>
