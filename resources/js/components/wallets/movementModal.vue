<template>
  <div v-if="movement">
    <transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">

            <div class="modal-header">
              <slot name="header">
                  <h3>Movement ID: {{ movement.id }} in date: {{ movement.date }}</h3>
                  <button
                    type="button"
                    class="btn-close"
                    @click="close"
                    aria-label="Close modal"
                  >
                    x
                  </button>
              </slot>
            </div>

            <div class="modal-body">
              <slot name="body">
                <div class="row">
                  <div class="col-md-4" v-if="movement.description"><h4>Description</h4>{{ movement.description }}</div>
                  <div class="col-md-4" v-if="movement.source_description"><h4>Source Description</h4>{{ movement.source_description }}</div>
                  <div class="col-md-4" v-if="movement.iban"><h4>IBAN</h4>{{ movement.iban }}</div>
                </div>
                <br><br>
                <div class="row">
                  <div class="col-md-3" v-if="movement.mb_entity_code"><h4>MB Entity Code</h4>{{ movement.mb_entity_code }}</div>
                  <div class="col-md-3" v-if="movement.mb_payment_reference"><h4>MB payment reference</h4>{{ movement.mb_payment_reference }}</div>
                  <div class="col-md-3" v-if="movement.transferEmail"><h4>Transfer User</h4>{{ movement.transferEmail }}</div>
                </div>
                <br><br>
                <div class="row">
                  <div class="col-md-4"><h4>Value</h4>{{ movement.value }}</div>
                  <div class="col-md-4"><h4>Start Balance</h4>{{ movement.start_balance }}</div>
                  <div class="col-md-4"><h4>End Balance</h4>{{ movement.end_balance }}</div>
                </div>
                <br><br>
              </slot>
            </div>

            <div class="modal-footer">
              <slot name="footer">
                <a class="btn btn-sm btn-primary" v-on:click.prevent="close">Close</a>
              </slot>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script type="text/javascript">
export default {
  name: 'modal',
  props: ["movement"],
  data: function() {
    return {
      

    };
  },
  methods: {
    close() {
      this.$emit('close');
    }
  },
  updated(){
    console.log(this.movement.id);
    console.log(this.movement.type);
    //console.log(this.categories);
  }
};
</script>
