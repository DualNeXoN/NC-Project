<link rel="stylesheet" href="./subpages/tickets/fragments/ticketNew/ticketNew.frag.css">

<div class="row justify-content-center divider-top">
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6 col-sm-8 col-10 text-center">
            <input type="button" id="btn-collapse-new-ticket" class="btn btn-primary full-width" data-bs-toggle="collapse" data-bs-target="#collapseNewTicket" aria-expanded="false" aria-controls="collapseNewTicket"></input>
        </div>
    </div>
</div>

<script src="./subpages/tickets/fragments/ticketNew/ticketNew.frag.js"></script>

<div class="row justify-content-center" style="margin-top: 8px">
    <div class="col-10 col-sm-8 col-md-6 text-center">
        <div class="collapse" id="collapseNewTicket">
            <div class="card card-body bg-dark" style="color: white">
                <form class="form-new-ticket" action="./includes/ticket.create.inc.php" method="post">
                    <div class="row justify-content-center">
                        <div class="col-sm-10 col-12" style="margin-top: 12px">
                            <label for="ticket-issue-id">Kategória problému</label>
                            <select class="form-control" name="ticket-issue-id" required>
                                <option disabled selected value> --- Vybrať kategóriu --- </option>
                                <?php

                                use Models\Ticket\TicketQueries as Query;

                                include './models/ticket.model.php';

                                $issues = Query::getAllIssues();
                                while ($row = mysqli_fetch_assoc($issues)) {
                                    echo '<option value="' . $row['id'] . '">' . $row['issueLabel'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-sm-10 col-12" style="margin-top: 24px">
                            <label for="ticket-message">Popis problému</label>
                            <textarea class="form-control form-message" type="text" name="ticket-message" placeholder="Správa" required></textarea>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-sm-10 col-12" style="margin-top: 12px">
                            <button class="btn btn-primary full-width" name="form-submit" type="submit">Poslať</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>