<template>
  <div v-if="movement">
    <transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">

            <div class="modal-header">
              <slot name="header">
                  <h3>Movement ID: {{ movement.id }} in date: {{ movement.date }}</h3><br>
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
                  <div class="col-md-12">
                    <h4>{{ (movement.transfer ? "Transfer" : movement.type_paymentString) }}</h4>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4" v-if="movement.description"><h5>Description</h5>{{ movement.description }}</div>
                  <div class="col-md-4" v-if="movement.source_description"><h5>Source Description</h5>{{ movement.source_description }}</div>
                  <div class="col-md-4" v-if="movement.iban"><h5>IBAN</h5>{{ movement.iban }}</div>
                </div>
                <br><br>
                <div class="row">
                  <div class="col-md-4" v-if="movement.mb_entity_code"><h5>MB Entity Code</h5>{{ movement.mb_entity_code }}</div>
                  <div class="col-md-4" v-if="movement.mb_payment_reference"><h5>MB payment reference</h5>{{ movement.mb_payment_reference }}</div>
                  <div class="col-md-4" v-if="movement.transfer_movement_id">
                    <h5>Transfer User</h5>
                    {{ movement.transferEmail }}
                    <img v-if="movement.transferPhoto" style="border-radius: 50%" :src="'/storage/fotos/' + movement.transferPhoto" width="40" height="40" alt/>
                  </div>
                </div>
                <br><br>
                <div class="row">
                  <div class="col-md-4"><h5>Value</h5>{{ movement.value }}</div>
                  <div class="col-md-4"><h5>Start Balance</h5>{{ movement.start_balance }}</div>
                  <div class="col-md-4"><h5>End Balance</h5>{{ movement.end_balance }}</div>
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
      userPhoto: ""
    };
  },
  methods: {
    close() {
      this.$emit('close');
    }
  }
};
</script>
