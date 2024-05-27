<?php
function connectToDB() {
    return new mysqli("localhost", "root", "", "socialnetwork");
}