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
	if r.Method != "GET" {
		return
	}

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

func clientsHandler(w http.ResponseWriter, r *http.Request) {
	if r.Method != "POST" {
		return
	}

	body := r.Body

	var reqBody *struct {
		Client string             `json:"client"`
		Likes  []enjin.Preference `json:"likes"`
	}

	raw := make([]byte, 0)

	body.Read(raw)

	if err := json.Unmarshal(raw, reqBody); err != nil {
	}

    fmt.Println(reqBody)

	err := proxy.CreateClient(reqBody.Client, reqBody.Likes)

	if err != nil {
	}
}

func main() {
	defer proxy.Close()

	http.HandleFunc("/clients/", clientsHandler)
	http.HandleFunc("/preferences/", preferencesHandler)
	http.HandleFunc("/rand/", randHandler)
	http.HandleFunc("/", handler)

	log.Fatal(http.ListenAndServe(":8080", nil))
}
