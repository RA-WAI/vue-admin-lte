<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Objection Detection</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="/admin/dashboard">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Objection Detection
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="col-12 mb-3">
                <div class="d-flex justify-content-between">
                    <div class="d-flex">
                        <button type="button" class="btn btn-primary">
                            <i class="fa fa-play" aria-hidden="true"></i>
                            Start Detection
                        </button>
                    </div>
                    <div></div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h1>Multiple object detection</h1>
                    <p>
                        Wait for the model to load before clicking the button to
                        enable the webcam - at which point it will become
                        visible to use.
                    </p>

                    <section
                        id="demos"
                        class="col-6 offset-3"
                        :class="{ invisible: isInvisible }"
                    >
                        <p>
                            Hold some objects up close to your webcam to get a
                            real-time classification! When ready click "enable
                            webcam" below and accept access to the webcam when
                            the browser asks (check the top left of your window)
                        </p>

                        <div id="liveView" class="camView">
                            <button
                                @click="enableCam"
                                :disabled="enableCamButton"
                            >
                                Enable Webcam
                            </button>
                            <video
                                id="webcam"
                                autoplay
                                muted
                                width="640"
                                height="480"
                            ></video>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import * as tf from "@tensorflow/tfjs";
import * as cocoSsd from "@tensorflow-models/coco-ssd";

const video = document.getElementById("webcam");
const liveView = document.getElementById("liveView");
const isInvisible = ref(true);
const enableCamButton = ref(false);

// Check if webcam access is supported.
function getUserMediaSupported() {
    return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
}

// If webcam supported, add event listener to button for when user
// wants to activate it to call enableCam function which we will
// define in the next step.
if (getUserMediaSupported()) {
    enableCamButton.value = true;
} else {
    console.warn("getUserMedia() is not supported by your browser");
}

// Enable the live webcam view and start classification.
function enableCam(event) {
    // Only continue if the COCO-SSD has finished loading.
    if (!model) {
        return;
    }

    // Hide the button once clicked.
    event.target.classList.add("removed");

    // getUsermedia parameters to force video but not audio.
    const constraints = {
        video: true,
    };

    // Activate the webcam stream.
    navigator.mediaDevices.getUserMedia(constraints).then(function (stream) {
        video.srcObject = stream;
        video.addEventListener("loadeddata", predictWebcam);
    });
}

// Placeholder function for next step.
function predictWebcam() {}

// Pretend model has loaded so we can try out the webcam code.
var model = true;
isInvisible.value = true;
</script>

<style scoped>
h1 {
    font-style: italic;
    color: #ff6f00;
}

video {
    display: block;
}

section {
    opacity: 1;
    transition: opacity 500ms ease-in-out;
}
.removed {
    display: none;
}

.invisible {
    opacity: 0.2;
}

.camView {
    position: relative;
    float: left;
    width: calc(100% - 20px);
    margin: 10px;
    cursor: pointer;
}

.camView p {
    position: absolute;
    padding: 5px;
    background-color: rgba(255, 111, 0, 0.85);
    color: #fff;
    border: 1px dashed rgba(255, 255, 255, 0.7);
    z-index: 2;
    font-size: 12px;
}

.highlighter {
    background: rgba(0, 255, 0, 0.25);
    border: 1px dashed #fff;
    z-index: 1;
    position: absolute;
}
</style>
