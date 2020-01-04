<template>
  <div class="modal-backdrop" v-if="movement">
    <div class="modal"
      role="dialog"
      aria-labelledby="modalTitle"
      aria-describedby="modalDescription"
    >
      <header
        class="modal-header"
        id="modalTitle"
      >
        <slot name="header">
          Movement ID: {{ movement.id }} in date: {{ movement.date }}
          <button
            type="button"
            class="btn-close"
            @click="close"
            aria-label="Close modal"
          >
            x
          </button>
        </slot>
      </header>
      <section
        class="modal-body"
        id="modalDescription"
      >
        <slot name="body">
          <div class="row">
            <div class="col-md-4" v-if="movement.description"><h3>Description</h3>{{ movement.description }}</div>
            <div class="col-md-4" v-if="movement.source_description"><h3>Source Description</h3>{{ movement.source_description }}</div>
            <div class="col-md-4" v-if="movement.iban"><h3>IBAN</h3>{{ movement.iban }}</div>
          </div>
          <br><br>
          <div class="row">
            <div class="col-md-3" v-if="movement.mb_entity_code"><h3>MB Entity Code</h3>{{ movement.mb_entity_code }}</div>
            <div class="col-md-3" v-if="movement.mb_payment_reference"><h3>MB payment reference</h3>{{ movement.mb_payment_reference }}</div>
            <div class="col-md-3" v-if="movement.transferEmail"><h3>Transfer User</h3>{{ movement.transferEmail }}</div>
          </div>
          <br><br>
          <div class="row">
            <div class="col-md-4"><h3>Value</h3>{{ movement.value }}</div>
            <div class="col-md-4"><h3>Start Balance</h3>{{ movement.start_balance }}</div>
            <div class="col-md-4"><h3>End Balance</h3>{{ movement.end_balance }}</div>
          </div>
          <br><br>
        </slot>
      </section>
      <footer class="modal-footer">
        <slot name="footer">
          <a class="btn btn-sm btn-primary" v-on:click.prevent="close">Close</a>
        </slot>
      </footer>
    </div>
  </div>
</template>

<script type="text/javascript">
export default {
  name: 'modal',
  props: ["movement"],
  data: function() {
    return {
      // MB payment reference (only for a transfer - photo ofthe user associated to the pair movement of the transfer) 
      // MB payment photo (only for a transfer - photo ofthe user associated to the pair movement of the transfer) 

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

<style>
  .modal-backdrop {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.3);
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .modal {
    background: #FFFFFF;
    box-shadow: 2px 2px 20px 1px;
    overflow-x: auto;
    display: flex;
    flex-direction: column;
  }

  .modal-header,
  .modal-footer {
    padding: 15px;
    display: flex;
  }

  .modal-header {
    border-bottom: 1px solid #eeeeee;
    color: #4AAE9B;
    justify-content: space-between;
  }

  .modal-footer {
    border-top: 1px solid #eeeeee;
    justify-content: flex-end;
  }

  .modal-body {
    position: relative;
    padding: 20px 10px;
  }

  .btn-close {
    border: none;
    font-size: 20px;
    padding: 20px;
    cursor: pointer;
    font-weight: bold;
    color: #4AAE9B;
    background: transparent;
  }

  .btn-green {
    color: white;
    background: #4AAE9B;
    border: 1px solid #4AAE9B;
    border-radius: 2px;
  }

  /* .modal-fade-enter,
  .modal-fade-leave-active {
    opacity: 0;
  }

  .modal-fade-enter-active,
  .modal-fade-leave-active {
    transition: opacity .5s ease
  } */
</style>