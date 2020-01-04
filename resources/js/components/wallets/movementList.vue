<template>
  <div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Type</th>
          <th>Transfer Email</th>
          <th>Transfer Type</th>
          <th>Category</th>
          <th>Date</th>
          <th>Value</th>
          <th>Start Balance</th>
          <th>End Balance</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="movement in movements.data" :key="movement.id" :class="{activerow: editingMovement === movement}">
          <td>{{ movement.id }}</td>
          <td>{{ movement.typeString }}</td>
          <td>{{ movement.transferEmail }}</td>
          <td>{{ movement.type_paymentString}}</td>
          <td>{{ movement.category_name }}</td>
          <td>{{ movement.date }}</td>
          <td>{{ movement.value }}</td>
          <td>{{ movement.start_balance }}</td>
          <td>{{ movement.end_balance }}</td>
          <td>
            <a class="btn btn-sm btn-primary" v-on:click.prevent="editMovement(movement)">Edit</a>
            <a class="btn btn-sm btn-primary" v-on:click.prevent="showModal(movement)">+Info</a>
          </td>
        </tr>
      </tbody>
      <pagination :data="movements" :limit=2 @pagination-change-page="getMovements" align="center">
        <span slot="prev-nav">Previous</span>
        <span slot="next-nav">Next</span>
      </pagination>
      
    </table>
    <modal
      :movement="modalMovement"
      v-show="isModalVisible"
      @close="closeModal"
    />
  </div>
</template>

<script type="text/javascript">
// Component code (not registered)
import modal from './movementModal.vue';
export default {
  // props: ["movements"],
  components: {
      modal,
    },
    data: function() {
    return {
      editingMovement: null,
      oldMovement: null,
      modalMovement: null,
      selected: ["Apache", "Cochise"],
      selected: false,
      isModalVisible: false,
      movements: {}
    };
  },
  methods: {
    editMovement: function(movement) {
      if (this.editingMovement != null){
        this.oldMovement = this.editingMovement;
        this.cancelEdit();
      }
      this.editingMovement = movement;
      this.$emit("edit-click", movement);
    },
    getMovements: function(page) {
      axios.get("api/movements/"+this.$store.state.user.id+"?page="+page, { "headers": { "Authorization": 'Bearer '.concat(this.$store.state.token) } })
        .then(({ data }) => (this.movements = data));
    },
    showModal: function(movement) {
      this.modalMovement = movement;
      this.isModalVisible = true;
    },
    closeModal() {
      //this.modalMovement = null;
      this.isModalVisible = false;
    },
    cancelEdit: function() {
      axios.get("api/movement/" + this.oldMovement.id, { "headers": { "Authorization": 'Bearer '.concat(this.$store.state.token) } }).then(response => {
        Object.assign(this.oldMovement, response.data.data);
        this.$emit("movement-canceled", this.oldMovement);
      });
    }
  },
  mounted() {
    this.getMovements();
  },
};
</script>

<style scoped>
  tr.activerow {
    background: #123456 !important;
    color: #fff !important;
  }

  .modal-backdrop {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    /* background-color: rgba(0, 0, 0, 0.3); */
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
</style>