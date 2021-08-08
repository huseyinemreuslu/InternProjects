<template>
    <div>
        <!-- EDİT POP-UP -->
        <div
            class="modal fade"
            id="editModal"
            tabindex="-1"
            v-if="showEdit"
            role="dialog"
            aria-labelledby="editModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit</h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                            @click="clear"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form v-if="this.editData">
                                <label v-show="false">{{getEditData}}</label>
                                <div>
                                    <label>{{languages[dataEdit.lang_id-1].lang_name}}</label>
                                    <input
                                        type="textarea"
                                        v-model="dataEdit.verb"
                                        class="form-control"
                                        placeholder="Write something amazing!"
                                    />
                                </div>
                                <br>
                                <div v-for="(child,i) in dataEdit.children" :key="i">
                                    <label>{{languages[child.lang_id-1].lang_name}}</label>
                                    <input
                                        type="textarea"
                                        v-model="child.verb"
                                        class="form-control"
                                        placeholder="Write something amazing!"
                                    />
                                    <br>
                                </div>
                                
                                <div v-for="newChild in newChildren" :key="newChild.lang_id">
                                    <label>{{languages[newChild.lang_id-1].lang_name}}</label>
                                    <input
                                        type="textarea"
                                        v-model="newChild.verb"
                                        class="form-control"
                                        placeholder="Write something amazing!"
                                    />
                                </div>
                                <br>
                                <label>Type: {{typeId.type_name}}</label>
                                <div v-if="responseError">
                                <div class="alert alert-danger"  role="alert">
                                    {{responseMsg}}
                                </div> 
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                            @click="clear"
                        >
                            Close
                        </button>
                        <button
                            type="button"
                            @click="update"
                            class="btn btn-primary"
                        >
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END EDİT POP-UP -->
        <!-- ADD POP-UP -->
        <div
            class="modal fade"
            id="addModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="addModalLabel"
            v-if="showAdd || showAddModal"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add</h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                            @click="clear"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                        <div class="form-group">
                            <div  class="form-group" v-if="langs">
                                <div v-for="i in langs.length" :key="i">
                                    <label v-if="i-1 == 0">Default: {{ langs[i-1]["lang_name"] }}</label>
                                    <label v-if="i-1 == 1" class="text-danger">Not: Write at least one corresponding meaning</label><br v-if="i-1 == 1"/><br v-if="i-1 == 1"/>
                                    <label v-if="i-1 != 0">{{langs[i-1]["lang_name"]}}</label>
                                    <input
                                        type="textarea"
                                        v-model="langs[i-1]['verb']"
                                        class="form-control"
                                    />
                                    <br />
                                </div>
                                <label>Type: {{typeId.type_name}}</label>
                            </div>
                            <div v-if="responseError">
                                <div class="alert alert-danger" role="alert">
                                    {{responseMsg}}
                                </div> 
                            </div>    
                        </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                            aria-label="Close"
                            @click="clear"
                        >
                            Close
                        </button>
                        <button
                            type="button"
                            @click="save"
                            aria-label="Close"
                            class="btn btn-primary"
                        >
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END ADD POP-UP -->
        <!-- SEARCH POP-UP -->
        <div class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>
        <!-- END SEARCH POP-UP -->
    </div>
</template>

<script>
export default {
    props: ["languages","typeId","editData","showAdd","showEdit","res"],
    mounted (){
        var a = this.typeId.id;

        this.langs = this.languages.map(function(element) {
            return {
                lang_id: element.id,
                lang_name: element.lang_name,
                verb: "",
                type: a,
                id:-1,
            };
        });
        
        this.showAddModal = this.showAdd;
        
        
    },  
    computed: {
        getEditData(){
            this.newChildren = Array.from(this.langs);
            this.newChildren.splice(0,1);
            if(this.editData){
                this.dataEdit = JSON.parse(JSON.stringify(this.editData));;
                this.dataEdit.children.forEach((element) => {
                    this.newChildren.forEach((element2,index) => {
                        if(element2.lang_id == element.lang_id){
                            this.newChildren.splice(index,1);
                        }
                    });
                });
            }
        },
    },
    data() {
        return {
            dataEdit: [],
            newChildren: [],
            emptyChildren: [],
            langs: null,
            responseSuccess: false,
            responseMsg: '',
            responseError: false,
            showAddModal:false,
            errorListener: true,
        };
    },
    methods: {
        clear(){
            this.langs = [];
            var a = this.typeId.id;
            this.langs = this.languages.map(function(element) {
                return {
                    lang_id: element.id,
                    lang_name: element.lang_name,
                    verb: "",
                    type: a
                };
            });
            this.responseError = false;
            this.responseSuccess = '';
            this.showAddModal = false;
            this.newChildren = Array.from(this.langs);
            this.newChildren.splice(0,1);
            this.errorListener = true;
        },
        showEd(lang) {
            this.langs.splice(lang - 1, 1);
        },
        async save() {
            axios
                .post('/create_wordlist', this.langs)
                .then(response => {
                    if(response.data.id == 200){
                        this.showAddModal=false;
                        $('#addModal').modal('hide');
                        this.$emit('update-table',response.data);     
                        this.clear();
                        this.responseError = true;
                    }
                    else{
                        this.responseMsg = response.data.status;
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        },
        async update() {
            this.langs = [];
            this.langs.push({'id':this.dataEdit.id,'lang_id':this.dataEdit.lang_id,'parent_id':this.dataEdit.parent_id,'type':this.dataEdit.type,'verb':this.dataEdit.verb});
            this.dataEdit.children.forEach(element => {
                this.langs.push(element);
            });
            if(this.langs.length != this.languages.length){
                this.newChildren.forEach(element => {
                    this.langs.push(element);
                });
            }
            axios
                .post('/update_wordList', this.langs)
                .then(response => {
                    if(response.data.id == 200 || response.data.id == 201){
                        this.$emit('update-table',response.data);
                        $('#editModal').modal('hide');
                        this.clear();
                    }
                    else{
                        $('showAddModal').modal('show');
                        this.responseError=true;
                        this.responseMsg = response.data.status;
                        this.errorListener = false;
                    }
                })
                .catch(error => {
                    this.responseError=true;
                    this.responseMsg = response.data.status;         
                });
        }
    }
};
</script>

<style></style>
