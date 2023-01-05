package main

import (
	"bufio"
	"encoding/json"
	"fmt"
	"log"
	"math/rand"
	"net/http"
	"os"
	"time"

	"github.com/torres-developer/bloqsenjin"
)

var (
	proxy *enjin.DriverProxy
)

func init() {
	config, err := enjin.CreateConfig("../../config/credentials.json")

	if err != nil {
		panic(err)
	}

	proxy = config.CreateProxy()

	file, err := os.Open("../../config/preferences")

	if err != nil {
		panic(err)
	}

	defer file.Close()

    preferences := make([]enjin.Preference, 0)
	scanner := bufio.NewScanner(file)
	for scanner.Scan() {
		preferences = append(preferences, enjin.Preference(scanner.Text()))
	}

    proxy.InitiateDatabase(preferences)
}

func handler(w http.ResponseWriter, r *http.Request) {
	fmt.Fprintf(w, "Hi there, I love %s!", r.URL.Path[1:])
}

func randHandler(w http.ResponseWriter, r *http.Request) {
	rand.Seed(time.Now().Unix())

	fmt.Fprintf(w, "%d", rand.Intn(5)+1)
}

func preferencesHandler(w http.ResponseWriter, r *http.Request) {
    refs, err := proxy.GetPreferences(false)
    preferences := make([]enjin.Preference, len(refs))
    for i, v := range refs {
        preferences[i] = v
    }

    if err != nil {
        w.WriteHeader(http.StatusInternalServerError)
    }

    json, err := json.Marshal(preferences)

    if err != nil {
        w.WriteHeader(http.StatusInternalServerError)
    }

    fmt.Fprintf(w, "%s", string(json))
    w.Header().Add("Content-Type", "application/json")
}

func main() {
    defer proxy.Close()

	http.HandleFunc("/preferences/", preferencesHandler)
	http.HandleFunc("/rand/", randHandler)
	http.HandleFunc("/", handler)

	log.Fatal(http.ListenAndServe(":8080", nil))
}
