<?php

namespace frontend\models\loan;

use common\models\loan\Loan;
use common\models\loan\LoanSearch as LoanSearchBase;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * LoanSearch represents the model behind the search form about `custom_modules\loan\models\Loan`.
 */
class LoanSearch extends LoanSearchBase
{

    /** @var  integer */
    public $init_type_strong;
    /** @var  integer */
    public $status_strong;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'lender_id', 'borrower_id', 'status', 'period', 'currency_type', 'init_type', 'created_at'], 'integer'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Loan::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'lender_id' => $this->lender_id,
            'borrower_id' => $this->borrower_id,
            'status' => $this->status,
            'amount' => $this->amount,
            'period' => $this->period,
            'currency_type' => $this->currency_type,
            'init_type' => $this->init_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere([
            'init_type' => $this->init_type_strong,
            'status' => $this->status_strong

        ]);



        return $dataProvider;
    }
}
