<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">New Task</div>
          <div class="card-body">
            <!-- Task Name -->
            <div class="form-group">
              <label for="task" class="col-sm-6 control-label">Task</label>

              <div class="col-sm-12">
                <input
                  type="text"
                  name="name"
                  id="task-name"
                  class="form-control"
                  placeholder="E.g. My Todo"
                  v-model="task"
                  @keyup.enter="addTask"
                >
              </div>
            </div>

            <!-- Add Task Button -->
            <div class="form-group">
              <div class="col-sm-6">
                <button type="submit" class="btn btn-secondary" @keyup.enter="addTask">
                  <i class="fa fa-plus"></i> Add Task
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            Pending
            <span class="float-right badge badge-danger">{{pendingCount}}</span>
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li
                class="list-group-item"
                v-for="t in tasks"
                :key="t.id"
                v-if="t.completed === false"
              >
                {{ t.task }}
                <a class="float-right" href="#" @click="completeTask(t.id)">
                  #{{t.id}}
                  <i class="fa fa-check"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            Completed
            <span class="float-right badge badge-success">{{completeCount}}</span>
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li
                class="list-group-item"
                v-for="t in tasks"
                :key="t.id"
                v-if="t.completed !== false"
              >
                {{ t.task }}
                <a class="float-right" href="#">#{{t.id}}</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      task: "",
      tasks: [],
      pendingCount: 0,
      completeCount: 0,
      host:
        document.head.querySelector('meta[name="host-url"]').content + "/api"
    };
  },
  mounted() {
    this.getTasks();
  },
  methods: {
    addTask() {
      axios
        .post(this.host + "/tasks", { task: this.task })
        .then(res => {
          this.getTasks();
          this.task = "";
        })
        .catch(err => {
          console.log(err);
        });
    },
    getTasks() {
      axios
        .get(this.host + "/tasks")
        .then(res => {
          this.tasks = res.data.data;

          this.pendingCount = this.tasks.filter(
            task => task.completed === false
          ).length;

          this.completeCount = this.tasks.filter(
            task => task.completed !== false
          ).length;
        })
        .catch(err => {
          console.log(err);
        });
    },
    completeTask(taskId) {
      axios
        .post(this.host + "/tasks/" + taskId, { _method: "PUT" })
        .then(res => {
          this.getTasks();
        })
        .catch(err => {
          console.log(err);
        });
    }
  }
};
</script>
