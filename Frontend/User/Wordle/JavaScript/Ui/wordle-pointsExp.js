//------ Points Exp Modal ------//

function showPointsExpModal(totalPoints, totalExp) {
    document.getElementById('points-earned').textContent = totalPoints;
    document.getElementById('exp-earned').textContent = totalExp;
    pointsExpModal.classList.remove('hidden');
}

export { showPointsExpModal }