<template>
  <div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Type</th>
          <th>Transfer Email</th>
          <th>Type</th>
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
      <pagination :data="movements" :limit=5 @pagination-change-page="getMovements" align="center">
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
      modalMovement: null,
      selected: ["Apache", "Cochise"],
      selected: false,
      isModalVisible: false,
      movements: {}
    };
  },
  methods: {
    editMovement: function(movement) {
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
</style>