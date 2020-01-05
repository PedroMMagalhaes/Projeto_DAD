<template>
  <div>
    <div class="row" v-if="movementsFilter">
      <div class="col-md-4">
        <h6>ID</h6>
        <input class="form-control" type="text" align="center" v-model="searchID" placeholder="Search ID" />
      </div>
      <div class="col-md-4">
        <h6>Type</h6>
        <select class="form-control" v-model="searchType">
          <option value="ALL">Select Type to Filter</option>
          <option value="Income">Income</option>
          <option value="Expense">Expense</option>
        </select>
        <br>
      </div>
      <div class="col-md-4" v-if="categories">
        <h6>Category</h6>
        <select class="form-control" v-model="searchCategory">
          <option default value="ALL">Select Category to Filter</option>
          <option value="0"></option>
          <option v-for = "category in categories" :value="category.id">{{category.name}}</option>
        </select>
        <br>
      </div>
    </div>
    <div class="row" v-if="movementsFilter">
      <div class="col-md-4">
        <h6>Payment Type</h6>
        <select class="form-control" v-model="searchTypePayment">
          <option value="ALL">Select Type of Payment to Filter</option>
          <option value="t"></option>
          <option value="c">Cash</option>
          <option value="bt">Bank Transfer</option>
          <option value="mb">MB Payment</option>
        </select>
      </div>
      <div class="col-md-4">
        <h6>Email</h6>
        <input class="form-control" type="text" align="center" v-model="searchTransferEmail" placeholder="Search Email" />
        <br>
      </div>
      <div class="col-md-4">
        <h6>Between</h6>
        <input class="form-control" type="date" align="center" v-model="searchDateStart" placeholder="Search Start Date">
        <input class="form-control" type="date" align="center" v-model="searchDateEnd" placeholder="Search End Date">
        <br>
      </div>
    </div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Type</th>
          <th>Transfer Email</th>
          <th>Payment Type</th>
          <th>Category</th>
          <th>Date</th>
          <th>Value</th>
          <th>Start Balance</th>
          <th>End Balance</th>
          <th>Actions</th>
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
    </table>
    <pagination v-if="!filtering" :data="movements" :limit=3 @pagination-change-page="getMovements" align="left">
      <span slot="prev-nav">Previous</span>
      <span slot="next-nav">Next</span>
    </pagination>
    <modal
      :movement="modalMovement"
      v-show="isModalVisible"
      @close="closeModal"
    />
  </div>
</template>

<script type="text/javascript">
import modal from './movementModal.vue';
export default {
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
      searchID: "",
      searchType: "",
      searchDateStart: "",
      searchDateEnd: "",
      searchCategory: "",
      searchTypePayment: "",
      searchTransferEmail: "",
      regExFilterID: "",
      regExFilterType: "",
      regExFilterTransferEmail: "",
      categories: null,
      filtering: false,
      movementsFilter: null,
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
    getMovementsFiltered: function(page) {
      axios.get("api/movements/"+this.$store.state.user.id, { "headers": { "Authorization": 'Bearer '.concat(this.$store.state.token) } })
        .then(({ data }) => (this.movementsFilter = data));
    },
    filterData: function(){
      var movementsAUX = this.movementsFilter.data;
      if (this.regExFilterID === "" && this.regExFilterType === "" && this.searchCategory === "" && this.searchTypePayment === "" && this.searchTransferEmail === "" && (this.searchDateStart === "" || this.searchDateEnd === "")){
        this.filtering = false;
        this.getMovements();
        this.getMovementsFiltered();
      }
      else
      {
        this.filtering = true;
        if (this.regExFilterID != "") {
          movementsAUX = movementsAUX.filter(movement => {
            return movement.id.toString().match(this.regExFilterID);
          });
        }
        if (this.regExFilterType != "") {
          movementsAUX = movementsAUX.filter(movement => {
            return movement.typeString.match(this.regExFilterType);
          });
        }
        if (this.searchCategory != "") {
          if (this.searchCategory === "0"){
            movementsAUX = movementsAUX.filter(movement => {
              return (movement.category_id === null);
            });
          }else{
            movementsAUX = movementsAUX.filter(movement => {
              return ((movement.category_id === null ? 0 : movement.category_id) === (this.searchCategory));
            });
          }
        }
        if (this.regExFilterTransferEmail != "") {
          movementsAUX = movementsAUX.filter(movement => {
            if (movement.transferEmail === null){
              return false;
            }
            return movement.transferEmail.match(this.regExFilterTransferEmail);
          });
        }
        if (this.searchTypePayment != "") {
          movementsAUX = movementsAUX.filter(movement => {
            return ((movement.type_payment === null ? "t" : movement.type_payment) === (this.searchTypePayment));
          });
        }
        if (this.searchDateStart != "" && this.searchDateEnd != "") {
          movementsAUX = movementsAUX.filter(movement => {
            
            var dateStart = this.searchDateStart;
            var dateEnd = this.searchDateEnd;
            var dateCheck = movement.date;
            dateCheck = dateCheck.slice(0,10)


            var d1 = dateStart.split("-");
            var d2 = dateEnd.split("-");
            var c = dateCheck.split("-");

            var start = new Date(d1[0], parseInt(d1[1])-1, d1[2]);
            var end   = new Date(d2[0], parseInt(d2[1])-1, d2[2]);
            var check = new Date(c[0], parseInt(c[1])-1, c[2]);
            return (check >= start && check <= end);
          });
        }
      }

      this.movements.data = movementsAUX;
    },
    showModal: function(movement) {
      this.modalMovement = movement;
      this.isModalVisible = true;
    },
    closeModal() {
      this.isModalVisible = false;
    },
    cancelEdit: function() {
      axios.get("api/movement/" + this.oldMovement.id, { "headers": { "Authorization": 'Bearer '.concat(this.$store.state.token) } }).then(response => {
        Object.assign(this.oldMovement, response.data.data);
        this.$emit("movement-canceled", this.oldMovement);
      });
    },
    getCategories: function(){
      axios.get("api/categories/"+this.searchType.toLowerCase().trim().slice(0, 1), { "headers": { "Authorization": 'Bearer '.concat(this.$store.state.token) } }).then(response => {
        this.categories = response.data;
      });
    }
  },
  mounted() {
    this.getMovements();
    this.getMovementsFiltered();
  },

  watch: {
    searchID: function(val) {
      this.regExFilterID = new RegExp(".*" + val + ".*", "i");
      if (val == "") {
         this.regExFilterID = "";
      }
      this.filterData();
    },

    searchType: function(val) {
      this.regExFilterType = new RegExp(".*" + val + ".*", "i");
      if (val == "" || val == "ALL") {
         this.regExFilterType = "";
         this.categories = null;
         this.searchCategory = "";
      }
      else {
        this.getCategories();
      }
      this.filterData();
    },
    
    searchCategory: function(val) {
      this.filterData();
    },
    
    searchTransferEmail: function(val) {
      this.regExFilterTransferEmail = new RegExp(".*" + val + ".*", "i");
      if (val == "") {
         this.regExFilterTransferEmail = "";
      }
      this.filterData();
    },
    
    searchTypePayment: function(val) {
      this.filterData();
    },

    searchDateStart: function(val) {
      this.filterData();
    },

    searchDateEnd: function(val) {
      this.filterData();
    }
  }
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