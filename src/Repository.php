<?php

namespace Collegeplannerpro\InterviewReport;

class Repository
{
    public function __construct(private \mysqli $db) {}

    public function allInvoices(): \mysqli_result
    {
        return $this->db->query(<<<SQL
            SELECT i.*, c.first_name, c.last_name,
            (SELECT SUM(amount) FROM payments p WHERE i.invoice_id = p.invoice_id) AS amount_paid,
            total - (SELECT amount_paid) AS balance
            FROM invoices i
            NATURAL JOIN contacts c
            ORDER BY i.issued_at
SQL
        );
    }

    public function invoicePayments($invoiceId): \mysqli_result
    {
        return $this->db->query(
            "SELECT * FROM payments WHERE invoice_id = $invoiceId ORDER BY paid_at ASC"
        );
    }

    public function contactDetails(int $contactId): ?array
    {
        return $this->db->query(
            "SELECT * FROM contacts WHERE contact_id = $contactId LIMIT 1"
        )->fetch_assoc() ?: null;
    }
}
