<template>

    <form @submit.prevent="sendMail" method="POST">
      <h1><strong>Broadcast Mail</strong></h1>
        <input   value="Exp" v-model="option" type="radio">
        <label > Expired Projects</label>
        <br>
        <input  value="Inp" v-model="option" type="radio">
        <label > In progress Projects</label>
        <br>
        <label >Subject Line: </label>
      <input
        required
        type="text"
        v-model="subject"

      >
      <br>
      <br>
        <hr>
      <textarea
        required
        placeholder="Type Content of your e-mail"
        v-model="content"
        rows="15"
      ></textarea>
      <br>
      <input type="submit" class="sub" name="Send Mail" />
    </form>

</template>

<script>
import axios from "axios";
export default {
  name: "App",
  components: {},
  data() {
    return{
    option: "",
    subject: "",
    content: "",
    };
  },
  methods: {
    sendMail() {
      if(!this.option )
      {
        alert("U should select Expired or In progress reports")
      }
      else if(!this.subject)
      {
        alert("U should type subject line")
      }
      else if(!this.content){
        alert("U should type conent of the E-mail")
      }
      else{
      console.log("E-mail broadcasted successfully");
      axios.post("email", {
        option: this.option,
        subject: this.subject,
        content: this.content,
      }).then(function(response) {
        console.log(response);
      }).catch(function(error) {
        console.log(error);
      });}
    },
  },
};
</script>
<style>
form {
  width: 700px;
  height: fit-content;
  margin: 30px auto;
  background: beige;
  text-align: left;
  padding: 40px;
  border-radius: 10px;
}
input {
  display: inline;
  border: none;
  border-bottom: 1px solid #ddd;
  height: 20px;
  color: #555;
}
h1 {
  color: #35605a;
  text-align: center;
}
.sub {
  border: 1px solid;
  border-color: #35605a;
  border-radius: 40px;
  text-align: center;
  width: 80px;
  line-height: 5px ;
  padding-top: 2px;
  transition: 0.5s background-color ease-in;
}
textarea{
    width: 100%;
}
.sub:hover{
    background-color: #09c;
}
</style>
