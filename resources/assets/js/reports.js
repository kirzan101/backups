let myChart = document.getElementById('myChart').getContext('2d');

let membersChart = new Chart(myChart, {
    type: 'bar', //bar,horizontal,pie,radar
    data: {
        labels: ['Green', 'Platinum'],
        datasets: [{
                label: 'Platinum',
                data: [
                    200
                ]
            },

            {
                label: 'Green',
                data: [
                    150
                ]
            },

        ]
    },
    options: {}
});

