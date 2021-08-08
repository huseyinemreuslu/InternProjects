<template>
    <div>
        <button type="button" @click="showAdd=true"   class="btn btn-info" data-toggle="modal" data-target="#addModal"><i class="fas fa-plus"></i></button>
        <br>
        <br>
        <!-- SEARCH -->
        <form class="form-inline my-2 my-lg-0" v-if="!isEmpty(this.words)" v-show="!isEmpty(this.list) ">
            <select class="custom-select" @change="onChange($event)" v-model="searchLangId"  id="inputGroupSelect01">
                <option v-for="ln in this.languages" :key="ln.id" :value="ln.id" >{{ln.lang_name}}</option>
            </select>
            <input class="form-control mr-sm-2" type="search" placeholder="Search" v-model="query" aria-label="Search">
        </form>
        <label >{{getQuery}}</label>
        <br>
        <br>
        <!-- END OF SEARCH -->
        <!-- ALERT -->
        <div v-show="responseError">
            <div class="alert alert-danger" role="alert">
                {{responseMsg}}
            </div> 
        </div>
        <!-- END OF ALERT -->
        <!-- TABLE -->
        <pop :languages="languages" :type-id="typeId"  @update-table="updateTable($event)"  :showEdit="showEdit" :showAdd="this.showAdd" :editData="editData" ></pop>
        <table class="table" v-if="!isEmpty(this.words)">
            <thead class="thead-dark">
                <tr>
                    <th v-for="(lan,i) in languages" :key="i">{{lan.lang_name}}</th>
                    <th>Edit / Delete</th>
                </tr>
            </thead>
            <tbody>
                <tr  v-for="(item,x) in words" :key="x">
                    <td class="col-md-3">
                        {{ item.verb }}
                    </td>
                    <td class="col-md-3" v-for="i in languages.length-1" :key="i">
                        <div v-for="child in item.children" :key="child.id"><div v-if="child.lang_id == languages[i].id">{{child.verb}}</div></div>
                    </td>
                    <td class="col-md-3">
                        <button
                            type="button"
                            @click="showEditModal(item,x)"
                            data-toggle="modal"
                            data-target="#editModal"
                            class="btn btn-primary"
                        >
                            Edit
                        </button>
                        <button
                            type="button"
                            data-target="#deleteModal"
                            data-toggle="modal"
                            @click="showDeleteModal(item,x)"
                            class="btn btn-danger"
                        >
                            Delete
                        </button>
                    </td> 
                </tr>
            </tbody>
        </table>
        <!-- END OF TABLE -->
        <!-- EMPTY -->
        <br /><br /><br /><br />
        <h4 v-if="isEmpty(this.words)">
            There is not data. click
            <a
                type="button"
                data-toggle="modal"
                data-target="#addModal"
                class="text-primary"
                @click="showAdd = true;"
            >
                here
            </a>
            to add
        </h4>
        <!-- END EMPTY -->
        <!-- DELETE MODAL -->
        
        <div v-if="deleteModal" id="deleteModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete ?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Do you want to delete?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"  @click="showDeleteModal=false" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" @click="deleteItem">Delete</button>
            </div>
            </div>
        </div>
        </div>
        <!-- END DELETE MODAL -->
    </div>
</template>

<script>
import pop from './pop.vue' 
export default {
    props: ["typeId","list", "languages"],
    mounted() {
        this.words = this.list;
        this.tempWords = this.words;
    },
    data() {
        return {
            words:[],
            tempWords:[],
            deleteModalElement: '',
            deleteModalIndex: '',
            editModalIndex: '',
            editData: '',
            searchLangId: 1,
            query: '',
            showEdit:false,
            showAdd:false,
            deleteModal: false,
            responseMsg: '',
            responseError: false
        };
    },
    computed: {
        getQuery(){
                if(this.query != ''){
                    fetch('/search_wordList?query='+this.query+'&type='+this.typeId.id+'&lang='+this.searchLangId)
                        .then(res => res.json())
                        .then(res => {
                            if(res.data.length != 0) {
                                this.words = res.data;
                            } else {
                                this.responseMsg = 'Search not found';
                                this.responseError = true;
                                setTimeout(this.showError,1500);
                            }
                        })
                        .catch(err => {
                            return err;
                    });
                }
                else {
                    this.words = this.tempWords;
                }
            },
    },
    methods: {
        showError() {
            this.responseError = false;
            this.responseMsg = '';
            this.words = this.tempWords;
        },
        showDeleteModal(element,index){
            this.deleteModal = true;
            this.deleteModalElement = element;
            this.deleteModalIndex = index;
        },
        showEditModal(data,index){
            this.showEdit = true;
            this.editData = data;
            this.editModalIndex = index;
        },
        onChange(event) {
            this.query = '';
            this.words = this.tempWords;
        },
        isEmpty(element){
            if(_.isEmpty(element)){
                return true;
            }
            else {
                return false;
            }
        },
        async updateTable(item) {
            var newItem = {"id":item[0].id,"lang_id":item[0].lang_id,"parent_id":item[0].parent_id,"type":item[0].type,"verb":item[0].verb,"children":item['children']};
            if(item.id == 200) {
                this.words.push(newItem);
            } else {
                this.words[this.editModalIndex].verb = newItem.verb;
                this.words[this.editModalIndex].children = newItem.children;
            }
        },
        deleteItem() {
            axios
                .post("/delete_wordList", this.deleteModalElement)
                .then(response => {
                    this.deleteModal = false;
                    $('#deleteModal').modal('hide');
                    this.words.splice(this.deleteModalIndex,1);
                })
                .catch(error => {
                    console.log(error);
                });
        },
        
    },
    components:{
        pop,
    }
};
</script>



