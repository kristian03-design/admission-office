<?php
// ============================================================
// app/Helpers/Validator.php  –  Simple server-side validator
// ============================================================

namespace App\Helpers;

class Validator
{
    private array $errors = [];
    private array $data   = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Validate a set of rules.
     *
     * Rules syntax (pipe-separated):  'required|email|max:191'
     *
     * Supported rules:
     *   required, string, integer, numeric, email, min:n, max:n,
     *   confirmed, in:a,b,c, date, boolean, file
     */
    public function validate(array $rules): self
    {
        foreach ($rules as $field => $ruleString) {
            $value     = $this->data[$field] ?? null;
            $fieldRules = explode('|', $ruleString);

            foreach ($fieldRules as $rule) {
                [$ruleName, $param] = array_pad(explode(':', $rule, 2), 2, null);

                $error = match ($ruleName) {
                    'required'  => $this->checkRequired($value, $field),
                    'string'    => $this->checkString($value, $field),
                    'integer'   => $this->checkInteger($value, $field),
                    'numeric'   => $this->checkNumeric($value, $field),
                    'email'     => $this->checkEmail($value, $field),
                    'min'       => $this->checkMin($value, $field, (int) $param),
                    'max'       => $this->checkMax($value, $field, (int) $param),
                    'confirmed' => $this->checkConfirmed($value, $field),
                    'in'        => $this->checkIn($value, $field, explode(',', $param ?? '')),
                    'date'      => $this->checkDate($value, $field),
                    'boolean'   => null, // any value can be cast to bool
                    'nullable'  => null, // allow null – skip other rules if null
                    default     => null,
                };

                // If nullable and value is null/empty, stop checking this field
                if ($ruleName === 'nullable' && ($value === null || $value === '')) break;

                if ($error) {
                    $this->errors[$field][] = $error;
                    break; // stop after first error per field
                }
            }
        }

        return $this;
    }

    public function fails(): bool  { return !empty($this->errors); }
    public function passes(): bool { return empty($this->errors); }
    public function errors(): array { return $this->errors; }

    /** Return sanitised input (strips tags, trims whitespace) */
    public function sanitised(): array
    {
        $clean = [];
        foreach ($this->data as $key => $value) {
            if (is_string($value)) {
                $value = trim(strip_tags($value));
            }
            $clean[$key] = $value;
        }
        return $clean;
    }

    // ── Rule implementations ──────────────────────────────────

    private function checkRequired(mixed $v, string $f): ?string
    {
        return ($v === null || $v === '') ? "$f is required" : null;
    }

    private function checkString(mixed $v, string $f): ?string
    {
        return ($v !== null && !is_string($v)) ? "$f must be a string" : null;
    }

    private function checkInteger(mixed $v, string $f): ?string
    {
        return ($v !== null && !filter_var($v, FILTER_VALIDATE_INT)) ? "$f must be an integer" : null;
    }

    private function checkNumeric(mixed $v, string $f): ?string
    {
        return ($v !== null && !is_numeric($v)) ? "$f must be numeric" : null;
    }

    private function checkEmail(mixed $v, string $f): ?string
    {
        return ($v !== null && !filter_var($v, FILTER_VALIDATE_EMAIL)) ? "$f must be a valid email" : null;
    }

    private function checkMin(mixed $v, string $f, int $min): ?string
    {
        if ($v === null) return null;
        if (is_numeric($v) && (float)$v < $min) return "$f must be at least $min";
        if (is_string($v)  && mb_strlen($v) < $min) return "$f must be at least $min characters";
        return null;
    }

    private function checkMax(mixed $v, string $f, int $max): ?string
    {
        if ($v === null) return null;
        if (is_numeric($v) && (float)$v > $max) return "$f must not exceed $max";
        if (is_string($v)  && mb_strlen($v) > $max) return "$f must not exceed $max characters";
        return null;
    }

    private function checkConfirmed(mixed $v, string $f): ?string
    {
        $confirm = $this->data["{$f}_confirmation"] ?? null;
        return ($v !== $confirm) ? "$f confirmation does not match" : null;
    }

    private function checkIn(mixed $v, string $f, array $options): ?string
    {
        return ($v !== null && !in_array($v, $options, true)) ? "$f must be one of: " . implode(', ', $options) : null;
    }

    private function checkDate(mixed $v, string $f): ?string
    {
        return ($v !== null && strtotime($v) === false) ? "$f must be a valid date" : null;
    }
}
