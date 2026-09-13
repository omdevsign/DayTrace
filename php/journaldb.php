<?php

function createJournalEntry($pdo, $user_id, $entry_date, $highlight, $growth, $gratitude, $tagsInput) {
    $stmt = $pdo->prepare("INSERT INTO journal_entries (user_id, entry_date, daily_highlight, area_for_growth, gratitude_statement) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $entry_date, $highlight, $growth, $gratitude]);
    $journal_id = $pdo->lastInsertId();

    if (!empty(trim($tagsInput))) {
        $tags = explode(',', $tagsInput);
        $stmtTag = $pdo->prepare("INSERT INTO journal_tags (journal_id, tag_name) VALUES (?, ?)");
        
        foreach ($tags as $tag) {
            $cleanTag = trim($tag);
            if (!empty($cleanTag)) {
                $stmtTag->execute([$journal_id, $cleanTag]);
            }
        }
    }
    return $journal_id;
}

function getJournalEntries($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM journal_entries WHERE user_id = ? ORDER BY entry_date DESC");
    $stmt->execute([$user_id]);
    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($entries as &$entry) {
        $stmtTag = $pdo->prepare("SELECT tag_name FROM journal_tags WHERE journal_id = ?");
        $stmtTag->execute([$entry['journal_id']]);
        $entry['tags'] = $stmtTag->fetchAll(PDO::FETCH_COLUMN);
    }
    return $entries;
}

function deleteJournalEntry($pdo, $journal_id) {
    $stmt = $pdo->prepare("DELETE FROM journal_entries WHERE journal_id = ?");
    return $stmt->execute([$journal_id]);
}
?>